<?php

namespace app\modules\media\controllers\frontend;

use app\modules\media\models\Audio;
use app\modules\media\models\search\AudioSearch;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Class AudioController
 * @package app\modules\media\controllers\frontend
 */
class AudioController extends Controller
{
    /**
     * Lists all Audio models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AudioSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, true);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Audio model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Audio model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Audio the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Audio::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
