<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 28.06.17
 * Time: 9:57
 */

namespace app\modules\council\controllers\console;

use app\modules\config\helpers\Config as ConfigHelper;
use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilMember;
use Yii;
use yii\console\Controller;

class ActiveDiscussionController extends Controller
{

    public function actionIndex()
    {
        if (ConfigHelper::has('council_subscribe_time')) {

            $time = ConfigHelper::getValue('council_subscribe_time');

            $now = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));

            if ($time != $now->format('H:i')) {
                return static::EXIT_CODE_NORMAL;
            }

            $todayDiscussionExists = CouncilDiscussion::find()->where([
                'date_begin' => $now->format('Y-m-d'),
            ])->exists();

            if (!$todayDiscussionExists) {
                return static::EXIT_CODE_NORMAL;
            }

            $list = CouncilDiscussion::find()->active()->asArray()->all();

            $users = CouncilMember::find()->where([
                'blocked' => CouncilMember::BLOCKED_NO,
            ])->asArray()->all();

            if ($list && $users) {
                foreach ($users as $user) {
                    if ($user['email'] && filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
                        Yii::$app
                            ->getMailer()
                            ->compose('@app/modules/council/mail/activeDiscussionList', [
                                'user' => $user,
                                'list' => $list,
                            ])
                            ->setSubject('Текущие активные общественные обсуждения')
                            ->setFrom(Yii::$app->params['email'])
                            ->setTo($user['email'])
                            ->send();
                    }
                    // send message to additional emails
                    if ($user['additional_email']) {
                        $mail_list = explode(",", $user['additional_email']);
                        foreach ($mail_list as $key => $email) {
                            $email = trim($email);
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                $mail_list[$key] = $email;
                            } else {
                                unset($mail_list);
                            }
                        }

                        if (!empty($mail_list)) {
                            Yii::$app
                                ->getMailer()
                                ->compose('@app/modules/council/mail/activeDiscussionList', [
                                    'user' => null,
                                    'list' => $list,
                                ])
                                ->setSubject('Текущие активные общественные обсуждения')
                                ->setFrom(Yii::$app->params['email'])
                                ->setTo($mail_list)
                                ->send();
                        }
                    }
                }

                return static::EXIT_CODE_NORMAL;
            }
        }

        return static::EXIT_CODE_ERROR;
    }
}