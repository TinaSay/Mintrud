<?php

namespace app\modules\page\controllers\backend;

use app\modules\page\models\Structure;
use app\modules\system\components\backend\Controller;

/**
 * Class StructureController
 * @package app\modules\page\controllers\backend
 */
class StructureController extends Controller
{
    /**
     * @var string
     */
    public $defaultAction = 'view';

    /**
     * @return string
     */
    public function actionUpdate()
    {
        $model = Structure::find()->limit(1)->one();
        if (!$model) {
            $model = new Structure();
        }

        if ($model->load(\Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return string|\yii\web\Response
     */
    public function actionView()
    {
        $model = Structure::find()->limit(1)->one();
        if (!$model) {
            return $this->redirect(['update']);
        }

        return $this->render('view', ['model' => $model]);
    }

}
