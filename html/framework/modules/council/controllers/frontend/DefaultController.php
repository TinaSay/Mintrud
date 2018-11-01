<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 11:02
 */

namespace app\modules\council\controllers\frontend;

use app\modules\council\models\CouncilMember;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;


class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//common';

    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'model' => Yii::$app->get('lk')->getIdentity(),
        ]);
    }

    /**
     * @return array
     */
    public function actionAddEmail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $email = Yii::$app->request->post('email');
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $added = false;
            /** @var CouncilMember $user */
            $user = Yii::$app->get('lk')->getIdentity();
            $emails = $user->getAdditionalEmailAsArray();
            if (!in_array($email, $emails)) {
                array_push($emails, $email);
                $added = true;
            }
            $user->setAttribute('additional_email', implode(",", $emails));
            $user->save();

            return ['success' => true, 'added' => $added, 'email' => $email];
        }

        return ['success' => false];
    }

    /**
     * @return array
     */
    public function actionRemoveEmail()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $email = Yii::$app->request->post('email');
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            /** @var CouncilMember $user */
            $user = Yii::$app->get('lk')->getIdentity();
            $emails = $user->getAdditionalEmailAsArray();
            if ($key = array_search($email, $emails)) {
                unset($emails[$key]);
                $user->setAttribute('additional_email', implode(",", $emails));
                $user->save();
            }

            return ['success' => true];
        }

        return ['success' => false];
    }

    /**
     * @return string
     */
    public function actionChangePassword()
    {
        /** @var CouncilMember $user */
        $user = Yii::$app->get('lk')->getIdentity();
        $user->setScenario($user::SCENARIO_PASSWORD_CHANGE);

        if ($user->load(Yii::$app->request->post()) && $user->validate()) {

            $user->save(false);
            Yii::$app->session->addFlash('success', 'Пароль изменен');

            return $this->redirect(['index']);
        }

        return $this->render('change-password', [
            'model' => $user,
        ]);
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLogout()
    {
        Yii::$app->get('lk')->logout(false);

        return $this->goHome();
    }

    /**
     * @param int $id
     * @return null|CouncilMember
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = CouncilMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}