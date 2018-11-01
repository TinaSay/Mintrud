<?php

namespace app\modules\cabinet\controllers\frontend;

use app\modules\cabinet\components\UserFactory;
use app\modules\cabinet\models\Client;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Model;
use yii\base\Module;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * Class ViewController
 *
 * @package app\modules\cabinet\controllers\frontend
 */
class ViewController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//cabinetInner';

    /**
     * @var UserFactory|null
     */
    protected $factory = null;

    /**
     * ViewController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param UserFactory $factory
     * @param array $config
     */
    public function __construct($id, Module $module, UserFactory $factory, array $config = [])
    {
        $this->factory = $factory;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $model = Yii::$app->getUser()->getIdentity();
        $log = $this->factory->model('Log');
        $lastLoginAt = $log::find()->lastLoginAt($model->getId());

        $changePasswordForm = $this->factory->form('ChangePassword');

        return $this->render('index', [
            'model' => $model,
            'changePassword' => $changePasswordForm,
            'lastLoginAt' => $lastLoginAt,
        ]);
    }

    /**
     * @return array|Response
     */
    public function actionChangePassword()
    {
        $model = Yii::$app->getUser()->getIdentity();

        /** @var Model $changePasswordForm */
        $changePasswordForm = $this->factory->form('ChangePassword');
        $changePasswordService = $this->factory->service('ChangePassword');

        if (Yii::$app->getRequest()->getIsAjax() && $changePasswordForm->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            return ActiveForm::validate($changePasswordForm);
        }

        if ($changePasswordForm->load(Yii::$app->request->post()) && $changePasswordForm->validate()) {
            $changePasswordService->execute($changePasswordForm, $model);
        }

        return $this->redirect(['index']);
    }

    /**
     * @return array|Response
     */
    public function actionChangeData()
    {
        /** @var Client $model */
        $model = Yii::$app->getUser()->getIdentity();

        /** @var Model $changePasswordForm */

        if (Yii::$app->getRequest()->getIsAjax() && $model->load(Yii::$app->getRequest()->post())) {
            Yii::$app->getResponse()->format = Response::FORMAT_JSON;

            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
        }

        return $this->redirect(['index']);
    }


    /**
     * @return string
     */
    public function actionSocials()
    {
        /** @var Client $model */
        $model = Yii::$app->getUser()->getIdentity();
        $log = $this->factory->model('Log');
        $lastLoginAt = $log::find()->lastLoginAt($model->getId());

        return $this->render('socials', [
            'model' => $model,
            'lastLoginAt' => $lastLoginAt,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->getUser()->logout(false);

        return $this->goHome();
    }
}
