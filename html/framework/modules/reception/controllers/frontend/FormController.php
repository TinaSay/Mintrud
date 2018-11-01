<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:13
 */

namespace app\modules\reception\controllers\frontend;

use app\modules\cabinet\form\VerifyCodeForm;
use app\modules\cabinet\helpers\PasswordGeneratorHelper;
use app\modules\cabinet\models\Client;
use app\modules\cabinet\models\VerifyCode;
use app\modules\cabinet\services\RegistrationWithVerifyService;
use app\modules\cabinet\services\VerifyCodeService;
use app\modules\config\helpers\Config as ConfigHelper;
use app\modules\reception\form\AppealForm;
use app\modules\reception\form\RegistrationWithVerifyForm;
use app\modules\reception\models\Appeal;
use app\modules\reception\services\SendAppealService;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\IdentityInterface;
use yii\web\Response;
use yii\web\UploadedFile;

class FormController extends Controller
{
    public $layout = '//common';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $textBefore = ConfigHelper::getValue('appeal_text_before');
        $textRight = ConfigHelper::getValue('appeal_text_right');

        $model = new AppealForm();
        if (!Yii::$app->get('user')->getIsGuest()) {
            /** @var Client $user */
            $user = Yii::$app->get('user')->getIdentity();
            $model->setEmail((string)$user->email);
            $model->setLastName((string)$user->getLastName());
            $model->setFirstName((string)$user->getFirstName());
            $model->setSecondName((string)$user->getMiddleName());
        }
        $post = Yii::$app->request->post();

        $appeal = new Appeal();
        if (!empty($post)) {
            $id = ArrayHelper::getValue($post, 'id');
            $oldAttachments = ArrayHelper::getValue($post, 'attachments', []);
            if ($id) {
                $appeal = Appeal::findOne($id);

                if (!$appeal) {
                    $appeal = new Appeal();
                }
            }
            $model->setAttachments(UploadedFile::getInstances($model, 'attachments'));
            // reattach saved files
            if (!$appeal->isNewRecord && $appeal->files && !empty($oldAttachments) && is_array($oldAttachments)) {
                foreach ($appeal->files as $file) {
                    if (in_array($file->id, $oldAttachments)) {
                        $model->addAttachmentAsPath($file->getUploadPath('src'));
                    } else {
                        $appeal->unlink('files', $file);
                        $file->delete();
                    }
                }
            }
            if ($model->load($post) && $model->validate()) {
                $service = new SendAppealService();
                if ($service->send($model, $appeal)) {
                    Yii::$app->session->addFlash('success', 'Ваше обращение подано');

                    return $this->redirect(['/reception/form', 'sent' => 'ok']);
                } else {
                    Yii::$app->session->addFlash('danger', 'Не удалось отправить обращение, попробуйте позже');
                };
            }
        }

        return $this->render('index', [
            'textBefore' => $textBefore,
            'textRight' => $textRight,
            'model' => $model,
            'appeal' => $appeal,
        ]);
    }

    /**
     * @param $country
     *
     * @return array
     */
    public function actionRegions($country)
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        return [
            'success' => true,
            'list' => AppealForm::getRegionAsDropDown($country),
        ];
    }

    /**
     * @return array
     */
    public function actionSendVerifyCode()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        if (!Yii::$app->getUser()->getIsGuest()) {
            return ['success' => true, 'registered' => true];
        }
        $email = Yii::$app->request->post('email');
        $form = new RegistrationWithVerifyForm([
            'email' => $email,
        ]);

        if ($form->validate(['email'])) {
            $retry = null;
            $service = new VerifyCodeService();
            // check & prevent multiple sending of code
            if (VerifyCode::find()->session()->exists()) {
                $verifyCoders = VerifyCode::find()->retryTimeout();
                $retry = $service->retryVerifyCodes($verifyCoders);
            } else {
                $service->sendVerifyCode($form, new VerifyCode());
            }

            return ['success' => !$form->hasErrors(), 'errors' => $form->getErrors(), 'retry' => !is_null($retry)];
        }

        return ['success' => false, 'errors' => $form->getErrors()];
    }

    /**
     * Верифицирует Email пользователя по коду
     * Для регистрации и Восстановления пароля
     *
     * @return array|Response
     */
    public function actionVerifyCode()
    {

        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        if (!Yii::$app->getUser()->getIsGuest()) {
            return ['success' => true, 'registered' => true];
        }

        $verifyCodeForm = new VerifyCodeForm();

        if (Yii::$app->getRequest()->getIsAjax() &&
            $verifyCodeForm->load(Yii::$app->getRequest()->post(), '') &&
            $verifyCodeForm->validate()
        ) {

            $service = new VerifyCodeService();
            $model = new VerifyCode();

            if ($service->successVerifyCode($verifyCodeForm, $model)) {
                $service = new RegistrationWithVerifyService();
                $email = Yii::$app->request->post('email');

                // do not auto register user if it already registered
                if (Client::find()->where([
                    'email' => $email,
                ])->exists()) {
                    VerifyCode::find()->cleanWithSessionId();

                    return ['success' => true, 'registered' => true];
                }

                /** @var IdentityInterface $model */
                $model = new Client();
                $verifyCode = new VerifyCode();
                $form = new RegistrationWithVerifyForm([
                    'email' => $email,
                    'password' => PasswordGeneratorHelper::generate(),
                ]);
                $form->setScenario($form::SCENARIO_REGISTRATION);

                if ($form->validate() && $service->registration($form, $model, true)) {
                    $verifyCode::find()->cleanWithSessionId();

                    Yii::$app->getUser()->login($model);

                    return ['success' => true, 'registered' => true];
                }

                return ['success' => false, 'errors' => $form->getErrors(), 'p' => $form->password];
            }
        }

        return ['success' => false, 'errors' => $verifyCodeForm->getErrors()];
    }

    /**
     * Повторно отправляет все коды верификации текущей sessionId
     * Увеличивает счетчик отправки +1
     * Если счетчик достиг лимита - удаляет запись верификации
     * Для регистрации и Восстановления пароля
     *
     * @return array|Response
     */
    public function actionRetryVerifyCodes()
    {
        Yii::$app->getResponse()->format = Response::FORMAT_JSON;

        $service = new VerifyCodeService();
        $model = new VerifyCode();

        $verifyCoders = $model::find()->retryTimeout();
        $count = $service->retryVerifyCodes($verifyCoders);
        $model::find()->retryLimit();

        return [
            'retry' => $count,
        ];
    }

    /**
     * @return Response
     */
    public function actionRefreshSession()
    {
        return $this->asJson(['success' => true]);
    }
}