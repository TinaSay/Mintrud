<?php

namespace app\modules\newsletter\controllers\frontend;

use app\modules\newsletter\models\Newsletter;
use yii\web\Response;
use Yii;

class DefaultController extends \yii\web\Controller
{
    public function actionSend()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new Newsletter();

        if(Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('Newsletter');
            $model = Newsletter::findOne(['email' => $post['email']]);
            if($model === null) {
                $model = new Newsletter();
            }

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return ['success' => true];
            }

        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

}
