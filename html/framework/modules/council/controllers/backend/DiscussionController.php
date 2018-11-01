<?php

namespace app\modules\council\controllers\backend;

use app\modules\council\models\CouncilDiscussion;
use app\modules\council\models\CouncilDiscussionSearch;
use app\modules\council\models\CouncilDiscussionVote;
use app\modules\council\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DiscussionController implements the CRUD actions for CouncilDiscussion model.
 */
class DiscussionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all CouncilDiscussion models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CouncilDiscussionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CouncilDiscussion model.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CouncilDiscussion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $now = new \DateTime();
        $model = new CouncilDiscussion([
            'vote' => CouncilDiscussion::VOTE_YES,
            'date_begin' => $now->format('Y-m-d'),
            'date_end' => $now->modify('+1 week')->format('Y-m-d'),
            'hidden' => CouncilDiscussion::HIDDEN_YES,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing CouncilDiscussion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {

            $votesDataProvider = new ActiveDataProvider([
                'query' => CouncilDiscussionVote::find()
                    ->joinWith('councilMember', true)
                    ->where([
                        'council_discussion_id' => $id,
                    ]),
            ]);

            return $this->render('update', [
                'model' => $model,
                'votesDataProvider' => $votesDataProvider,
            ]);
        }
    }

    /**
     * @param $id
     *
     * @return Response
     */
    public function actionExport($id)
    {
        $model = $this->findModel($id);

        $service = new ExportService(
            Yii::getAlias('@app/modules/council/data/vote-export.xlsx'),
            'Excel2007'
        );

        $service->exportExcel($model);

        return $service->sendFile("Результаты голосования.xlsx");
    }

    /**
     * Deletes an existing CouncilDiscussion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CouncilDiscussion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return CouncilDiscussion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CouncilDiscussion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
