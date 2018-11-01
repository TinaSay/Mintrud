<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 19:28
 */

declare(strict_types=1);

namespace app\modules\questionnaire\controllers\backend;


use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\ResultAnswer;
use app\modules\questionnaire\models\ResultAnswerText;
use app\modules\questionnaire\models\search\ResultSearch;
use app\modules\questionnaire\services\ExportService;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Class ResultController
 *
 * @package app\modules\questionnaire\controllers\backend
 */
class ResultController extends Controller
{
    /**
     * @var
     */
    private $questions;

    /**
     * @param int $id
     *
     * @return string
     */
    public function actionIndex(int $id): string
    {
        $searchModel = new ResultSearch();
        $searchModel->questionnaire_id = $id;

        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }

    /**
     * @param int $id
     *
     * @return string
     */
    public function actionView(int $id): string
    {
        $resultAnswers = $this->findResultAnswerByResult($id);
        $resultAnswerTexts = ResultAnswerText::find()->result($id)->all();

        return $this->render(
            'view',
            [
                'resultAnswers' => $resultAnswers,
                'resultAnswerTexts' => $resultAnswerTexts,
            ]
        );
    }

    public function actionExport(int $id)
    {
        $this->layout = false;

        $questionnaire = Questionnaire::findOne($id);
        $this->questions = $questionnaire->questions;
        $results = $questionnaire->results;

        Yii::$app->getResponse()->sendContentAsFile(
            $this->render(
                'export',
                [
                    'questionnaire' => $questionnaire,
                    'results' => $results,
                ]
            ),
            'questionnaire_results.html',
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
            Yii::getAlias('@app/modules/questionnaire/data/vote-export-1.xlsx'),
            'Excel2007'
        );

        $service->setExport($model->id);

        Yii::$app->session->addFlash('success', 'Задача для экпорта поставлена в очередь');

        return $this->redirect(['/questionnaire/questionnaire/view', 'id' => $id]);
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
                Yii::getAlias('@app/modules/questionnaire/data/vote-export-1.xlsx'),
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

        return $this->redirect(['/questionnaire/questionnaire/view', 'id' => $id]);
    }

    /**
     * @param int $id
     *
     * @return array
     */
    public function findResultAnswerByResult(int $id): array
    {
        $resultAnswers = ArrayHelper::index(ResultAnswer::find()->result($id)->all(), 'id', 'question_id');
        $this->loadQuestion(array_keys($resultAnswers));

        return $resultAnswers;
    }

    /**
     * @param array $ids
     */
    public function loadQuestion(array $ids): void
    {
        $this->questions = Question::find()->inId($ids)->indexBy('id')->all();
    }

    /**
     * @param int $id
     *
     * @return Question
     * @throws Exception
     */
    public function getQuestionById(int $id): Question
    {
        if (isset($this->questions[$id])) {
            return $this->questions[$id];
        } else {
            throw new Exception('Failed to view the report for unknown reason');
        }
    }

    /**
     * Finds the Questionnaire model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Questionnaire the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questionnaire::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}