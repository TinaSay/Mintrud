<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 9:55
 */

namespace app\modules\council\controllers\console;

use app\modules\config\helpers\Config as ConfigHelper;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\services\ExportService;
use DateTime;
use Yii;
use yii\console\Controller;

class ResultController extends Controller
{

    public function actionIndex()
    {
        $this->log('start action: ' . $this->getUniqueId());

        if (ConfigHelper::has('council_export_email')) {
            $email = ConfigHelper::getValue('council_export_email');
            $period = (int)ConfigHelper::getValue('council_export_period');

            $now = new DateTime('now', new \DateTimeZone('Europe/Moscow'));

            $list = CouncilDiscussion::find()->active()->all();

            $this->log('Found discussions: ' . count($list));
            if ($list) {
                foreach ($list as $model) {
                    $this->log('process discussion: ' . $model->title);

                    $dateBegin = DateTime::createFromFormat('Y-m-d', $model->date_begin);
                    $this->log('now: ' . $now->format('Y-m-d') . ' vs ' . $model->date_end);

                    if ($model->date_end == $now->format('Y-m-d') ||
                        $dateBegin && $now->diff($dateBegin)->days % $period === 0
                    ) {
                        $this->log('try to send email: ' . $email);

                        $service = new ExportService(
                            Yii::getAlias('@app/modules/council/data/vote-export.xlsx'),
                            'Excel2007'
                        );

                        $service->exportExcel($model);

                        $mailer = Yii::$app
                            ->getMailer()
                            ->compose('@app/modules/council/mail/resultEmail', [
                                'model' => $model,
                            ])
                            ->setSubject('Результаты голосования для общественного обсуждения ' . $model->title)
                            ->setFrom(Yii::$app->params['email'])
                            ->setTo($email);
                        $this->log('tmp file: ' . $service->getFile());

                        $mailer->attach($service->getFile(), ['fileName' => 'Результаты голосования.xlsx']);
                        $sent = $mailer->send();
                        if (!$sent) {
                            $this->log('mail not sent');
                        }

                        $service->unlinkFile();

                        unset($service);
                    } else {
                        $this->log('not now');
                    }
                }

                return static::EXIT_CODE_NORMAL;

            } else {
                $this->log('Not configured');
            }
            $this->log('No discussions');
        }

        return static::EXIT_CODE_ERROR;
    }

    /**
     * @param string $message
     */
    private function log($message)
    {
        if (YII_DEBUG) {
            fwrite(STDOUT, $message . PHP_EOL);
        }
    }
}