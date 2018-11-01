<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 19:38
 */

namespace app\modules\staticVote\controllers\backend;


use app\modules\staticVote\models\StaticVoteAnswers;
use app\modules\staticVote\models\StaticVoteQuestion;
use app\modules\staticVote\models\StaticVoteQuestionnaire;
use app\modules\staticVote\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class ManageController extends Controller
{
    /**
     * @param \yii\base\Action $action
     *
     * @return bool
     */
    public function beforeAction($action)
    {

        if ($this->action == 'export' && Yii::$app->getModule('debug')) {
            Yii::$app->getModule('debug')->getInstance()->allowedIPs = [];
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all StaticVoteQuestionnaire models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => StaticVoteQuestionnaire::find()->orderBy([
                'id' => SORT_ASC,
            ]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $exportFiles = glob(Yii::getAlias(ExportService::EXPORT_PATH) . '/*.xlsx');

        $dataProvider = new ActiveDataProvider([
            'query' => StaticVoteAnswers::find()->orderBy([
                'id' => SORT_ASC,
            ])->where([
                'questionnaire_id' => $id,
            ]),
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'exportFiles' => $exportFiles,
        ]);
    }

    /**
     * @param $file
     *
     * @return \yii\web\Response
     */
    public function actionDownload($file)
    {
        if (file_exists(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file)) {
            $service = new ExportService(
                Yii::getAlias('@app/modules/staticVote/data/vote-export-1.xlsx'),
                'Excel2007'
            );


            return $service->sendFile(
                Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file,
                'questionnaire_result_' . date('Y-m-d') . '.xlsx'
            );
        }

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @param $file
     *
     * @return \yii\web\Response
     */
    public function actionDeleteFile($id, $file)
    {
        if (file_exists(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file)) {
            @unlink(Yii::getAlias(ExportService::EXPORT_PATH) . '/' . $file);
        }

        return $this->redirect(['view', 'id' => $id]);
    }


    /**
     * Updates an existing News model.
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
            return $this->render(
                'update',
                [
                    'model' => $model,
                ]
            );
        }
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAnswers($id)
    {
        $answer = StaticVoteAnswers::findOne($id);
        if (!$answer) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = $this->findModel($answer->questionnaire_id);

        $questions = StaticVoteQuestion::find()->indexBy('id')->all();

        return $this->render('answers', [
            'model' => $model,
            'answers' => $answer->questionnaire,
            'questions' => $questions,
        ]);
    }

    /**
     * @param $id
     */
    public function actionExport($id)
    {
        $this->layout = false;

        $model = $this->findModel($id);

        $questions = StaticVoteQuestion::find()->indexBy('id')->all();


        Yii::$app->getResponse()->sendContentAsFile(
            $this->render('export', [
                'model' => $model,
                'list' => StaticVoteAnswers::find()->orderBy([
                    'id' => SORT_ASC,
                ])->where([
                    'questionnaire_id' => $id,
                ])->all(),
                'questions' => $questions,
            ]),
            'vote_results.html',
            ['mimeType' => 'text/html']
        )->send();

        Yii::$app->end();
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionExportXls($id)
    {
        $model = $this->findModel($id);

        $service = new ExportService(
            Yii::getAlias('@app/modules/staticVote/data/vote-export-1.xlsx'),
            'Excel2007'
        );

        $service->setExport($model->id);

        Yii::$app->session->addFlash('success', 'Задача для экпорта поставлена в очередь');

        return $this->redirect(['view', 'id' => $id]);
    }

    /**
     * @param int $id
     *
     * @return null|StaticVoteQuestionnaire
     * @throws \yii\web\NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = StaticVoteQuestionnaire::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}