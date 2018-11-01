<?php

namespace app\modules\subscribeSend\controllers\console;

use app\modules\event\models\Event;
use app\modules\news\models\News;
use app\modules\newsletter\models\Newsletter;
use app\modules\subscribeSend\models\SubscribeSend;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\helpers\Url;

class SubscribeSendController extends Controller
{
    public $defaultAction = 'mail';

    /**
     * init controller
     */
    public function init()
    {
        parent::init();
        ini_set('memory_limit', '128M');
        ini_set('max_execution_time', 600);
    }

    /**
     * @param int $flag
     * @param string $module
     *
     * @return int
     */
    public function actionMail($flag = Newsletter::TIME_DAILY, $module = SubscribeSend::NEWS_CLASS)
    {

        if ($module == SubscribeSend::NEWS_CLASS) {
            $model = new News();
        } elseif ($module == SubscribeSend::EVENT_CLASS) {
            $model = new Event();
        } else {
            print 'Module not found.' . PHP_EOL;

            return ExitCode::UNSPECIFIED_ERROR;
        }

        $perSend = 50; // Сколько писем уходит за 1 раз
        $pausePerMail = 10000; // Время между отправкой каждого письма

        //Выставляем дату для запроса
        if ($flag == Newsletter::TIME_NOW) {
            $dateByFlag = SubscribeSend::getLastSendByFlagTime($flag, get_class($model));
        } else {
            $dateByFlag = date('Y-m-d 17:30:00', strtotime(SubscribeSend::getMinusDay($flag)));
        }
        if (!$dateByFlag) {
            $dateByFlag = $model::find()->select(['created_at'])->orderBy(['created_at' => SORT_DESC])->scalar();
        }
        if (!$dateByFlag) {
            print 'Нечего рассылать.' . PHP_EOL;

            return ExitCode::OK;
        }
        $date = new \DateTime($dateByFlag);
        $dateByFlagUnix = $date->getTimestamp();

        //Считаем количество подписчиков в нужной модели
        if ($module == SubscribeSend::NEWS_CLASS) {
            $getSubscribers = Newsletter::find()->where(['time' => $flag])
                ->andWhere(['isNews' => Newsletter::IS_NEWS_YES]);
        } elseif ($module == SubscribeSend::EVENT_CLASS) {
            $getSubscribers = Newsletter::find()->where(['time' => $flag])
                ->andWhere(['isEvent' => Newsletter::IS_EVENT_YES]);
        }
        /** @var ActiveQuery $getSubscribers */
        $countSubscribers = $getSubscribers->count();

        $modelRecord = $model::find()
            ->leftJoin(SubscribeSend::tableName(),
                SubscribeSend::tableName() . '.[[recordId]] = ' . new Expression($model::tableName() . '.[[id]]') .
                ' AND ' . SubscribeSend::tableName() . '.[[model]] = :class' .
                ' AND ' . SubscribeSend::tableName() . '.[[statusTime]] = :time',
                [':class' => get_class($model), ':time' => $flag]
            )->hidden()->andWhere([
                '>=',
                new Expression('UNIX_TIMESTAMP(' . $model::tableName() . '.[[created_at]])'),
                $dateByFlagUnix,
            ])->andWhere([
                'IS',
                SubscribeSend::tableName() . '.[[id]]',
                null,
            ])->all();


        $arrDataSend = [];
        $batchInsert = [];
        $recordId = [];
        $now = date('Y-m-d H:i:s');
        if (count($modelRecord) <= 0) {
            print 'Нечего рассылать.' . PHP_EOL;

            return ExitCode::OK;
        }

        foreach ($modelRecord as $key => $record) {
            $arrDataSend[$key]['date'] = Yii::$app->formatter->asDate($record->created_at, 'dd MMMM yyyy');
            if ($module == SubscribeSend::NEWS_CLASS) {
                $arrDataSend[$key]['url'] = Yii::$app->urlManager->hostInfo . '/' . $record->getUrl();
            } else {
                if ($module == SubscribeSend::EVENT_CLASS) {
                    $arrDataSend[$key]['url'] = Url::to(['/events/event/view', 'id' => $record->id], true);
                }
            }
            $arrDataSend[$key]['title'] = $record->title;
            array_push($recordId, $record->id);

            $batchInsert[] = [get_class($record), $record->id, $flag, $now, $now];
        }

        if (!empty($arrDataSend)) {
            $loopCount = ceil($countSubscribers / $perSend);

            try {

                // prevent next mailing tick
                Yii::$app->db->createCommand()->batchInsert(SubscribeSend::tableName(),
                    ['model', 'recordId', 'statusTime', 'created_at', 'updated_at'],
                    $batchInsert)
                    ->execute();

                for ($i = 0; $i < $loopCount; $i++) {
                    $start = $perSend * $i;
                    $modelSubscribers = $getSubscribers->limit($perSend)->offset($start)->all();
                    foreach ($modelSubscribers as $subscriber) {

                        Yii::$app
                            ->mailer
                            ->compose('@app/modules/subscribeSend/mail/subscribeMailPattern', [
                                'arrDataSend' => $arrDataSend,
                                'module' => $module,
                            ])
                            ->setSubject('Новостная рассылка Минтруда')
                            ->setFrom(Yii::$app->params['email'])
                            ->setTo($subscriber->email)
                            ->send();

                        usleep($pausePerMail);
                    }

                }

                print 'Рассылка закончена' . PHP_EOL;

            } catch (\Exception $e) {
                if ($recordId) {
                    SubscribeSend::deleteAll([
                        'model' => get_class($model),
                        'recordId' => $recordId,
                        'statusTime' => $flag,
                    ]);
                }
                echo $e->getMessage() . PHP_EOL . $e->getTraceAsString() . PHP_EOL;
                Yii::error($e->getMessage());
            }
        }

        return ExitCode::OK;
    }
}
