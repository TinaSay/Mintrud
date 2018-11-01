<?php

namespace app\modules\council\controllers\backend;

use app\modules\council\models\LogSearch;
use app\modules\system\components\backend\Controller;
use Yii;

/**
 * LogController implements the CRUD actions for Log model.
 */
class LogController extends Controller
{
    /**
     * Lists all Log models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
