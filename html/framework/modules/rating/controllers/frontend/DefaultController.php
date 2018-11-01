<?php

namespace app\modules\rating\controllers\frontend;

use app\modules\system\components\frontend\Controller;
use Yii;
use yii\web\Response;
use app\modules\rating\models\Rating;
use yii\db\ActiveRecord;
use app\interfaces\RatingInterface;
use yii\web\BadRequestHttpException;

/**
 * Default controller for the `rating` module
 */
class DefaultController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * @return array|string
     * @throws \yii\web\BadRequestHttpException
     */
    public function actionRating()
    {
        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $module = Yii::$app->request->post('module');
            $record_id = Yii::$app->request->post('record_id');
            $rating = Yii::$app->request->post('rating');


            if ($rating === null) {
                return '';
            }
            if (empty($rating)) {
                $rating = 0;
            }

            $user_id = null;
            $user_ip = null;
            if (Yii::$app->getUser()->getIsGuest()) {
                $user_ip = Yii::$app->request->userIP;
            } else {
                $user_id = Yii::$app->getUser()->getId();
            }

            $model = Yii::createObject($module);
            if ($model instanceof ActiveRecord) {
                $record = $model::findOne($record_id);
            } else {
                throw new BadRequestHttpException('Bad module');
            }
            if ($record instanceof RatingInterface) {
                $title = $record->getTitle();
            } else {
                throw new BadRequestHttpException('Bad record');
            }

            $model = new Rating([
                'title' => $title,
                'module' => $module,
                'record_id' => $record_id,
                'user_ip' => $user_ip,
                'user_id' => $user_id,
                'rating' => $rating,
            ]);
            if ($model->save()) {
                $rating = Rating::getAvgRating($module, $record_id);
                return [
                    'status' => 'ok',
                    'rating' => $rating,
                ];
            } else {
                return [
                    'status' => 'error',
                ];
            }
        }
        return '';
    }
}
