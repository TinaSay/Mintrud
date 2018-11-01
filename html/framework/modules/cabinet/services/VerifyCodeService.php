<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.07.17
 * Time: 14:04
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\components\AbstractVerifyCode;
use app\modules\cabinet\components\VerifyCodeInterface;
use app\modules\cabinet\form\VerifyCodeForm;
use app\modules\cabinet\models\VerifyCode;
use Yii;
use yii\db\ActiveRecordInterface;

/**
 * Class VerifyCodeService
 *
 * @package app\modules\cabinet\services
 */
class VerifyCodeService
{
    /**
     * @param AbstractVerifyCode $form
     * @param VerifyCodeInterface $model
     */
    public function sendVerifyCode(AbstractVerifyCode $form, VerifyCodeInterface $model)
    {
        $form->setScenario($form::SCENARIO_VERIFY_CODE);
        if (!$form->existsEmailInVerify()) {
            // ensure that session is opened
            if (!Yii::$app->getSession()->isActive) {
                Yii::$app->getSession()->open();
            }
            /** @var $model ActiveRecordInterface|VerifyCodeInterface */
            $model->setAttribute('session_id', Yii::$app->getSession()->getId());
            $model->setAttribute('attribute', $form->getEmail());
            $model->setAttribute('code', sprintf('%0' . $form::CODE_LENGTH_MAX . 'd', mt_rand(1, 9999)));
            $model->setEmail($form->getEmail());

            if ($model->save()) {
                $this->sendVerifyCodeWithEmail($model);
            } else {
                $form->addError('email', 'Ошибка создания проверочного кода');
            }
        } else {
            $models = VerifyCode::find()->where(['attribute' => $form->getEmail()])->all();
            if ($models) {
                $this->retryVerifyCodes($models);
            }
        }
    }

    /**
     * @param array VerifyCode[] $verifyCodes
     *
     * @return int
     */
    public function retryVerifyCodes(array $verifyCodes)
    {
        foreach ($verifyCodes as $verifyCode) {
            // there is we must update session id for those codes
            $verifyCode->session_id = Yii::$app->getSession()->getId();
            $verifyCode->retry = $verifyCode->retry + 1;
            $verifyCode->save();
            $this->sendVerifyCodeWithEmail($verifyCode);
        }

        return count($verifyCodes);
    }

    /**
     * @param VerifyCodeInterface $model
     *
     * @return bool
     */
    protected function sendVerifyCodeWithEmail(VerifyCodeInterface $model)
    {
        $mailer = Yii::$app
            ->getMailer()
            ->compose('@app/modules/cabinet/mail/verifyCode.php', [
                'model' => $model,
            ])
            ->setSubject('Подтверждение Email')
            ->setFrom(Yii::$app->params['email'])
            ->setTo($model->getEmail());

        return $mailer->send();
    }

    /**
     * @param VerifyCodeForm $form
     * @param ActiveRecordInterface $model
     *
     * @return bool
     */
    public function successVerifyCode(VerifyCodeForm $form, ActiveRecordInterface $model)
    {
        $record = $model::findOne([
            'session_id' => $form->sessionId,
            'code' => $form->code,
        ]);

        $record->setAttribute('is_verify', $form::IS_VERIFY_YES);

        return $record->save();
    }
}
