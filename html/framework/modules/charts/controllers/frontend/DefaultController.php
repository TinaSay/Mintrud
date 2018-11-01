<?php
namespace app\modules\charts\controllers\frontend;

use app\modules\system\components\frontend\Controller;
use yii\web\NotFoundHttpException;
use zima\charts\models\Chart;

/**
 * Class DefaultController
 * @package app\modules\charts\controllers\frontend
 */
class DefaultController extends Controller
{
    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Chart::findOne(['id' => $id]);

        if (!$model) {
            throw new NotFoundHttpException('Страница не найдена!');
        }

        return $this->renderAjax('view', [
            'model' => $model,
        ]);
    }
}