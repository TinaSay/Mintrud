<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.10.17
 * Time: 14:31
 */

namespace app\modules\testing\controllers\frontend;

use app\modules\system\components\frontend\Controller;
use app\modules\testing\models\Testing;
use app\modules\testing\models\TestingQuestion;
use app\modules\testing\models\TestingQuestionAnswer;
use app\modules\testing\models\TestingQuestionCategory;
use app\modules\testing\models\TestingResult;
use app\modules\testing\models\TestingResultAnswer;
use Yii;
use yii\caching\TagDependency;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            Yii::$app->language,
        ];

        $query = Testing::find()->active()
            ->orderBy(['createdAt' => SORT_DESC]);

        $pagination = new Pagination([
            'pageSize' => 10,
            'totalCount' => $query->count(),
        ]);

        $query->limit($pagination->limit);
        $query->offset($pagination->offset);


        $dependency = new TagDependency([
            'tags' => [
                Testing::class,
            ],
        ]);

        $list = Yii::$app->cache->getOrSet($key, function () use ($query) {
            return $query->all();
        }, null, $dependency);

        return $this->render('index', ['list' => $list, 'pagination' => $pagination]);
    }

    /**
     * @param $id
     *
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionAnswer($id)
    {
        $key = [
            __CLASS__,
            __METHOD__ .
            __LINE__,
            Yii::$app->request->queryParams,
        ];

        $dependency = new TagDependency([
            'tags' =>
                [
                    Testing::className(),
                    TestingQuestion::className(),
                    TestingQuestionCategory::className(),
                    TestingQuestionAnswer::className(),
                ],
        ]);

        $model = $this->findModel($id);

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        /*
                if ($model->isAnswered()) {
                    return $this->render('is_answered', ['model' => $model]);
                }
        */

        if (!$data = Yii::$app->cache->get($key)) {
            $questions = TestingQuestion::find()
                ->joinWith('answers', true, 'INNER JOIN')
                ->joinWith('category', false)
                ->where([
                    TestingQuestion::tableName() . " . [[testId]]" => $id,
                    TestingQuestion::tableName() . " . [[hidden]]" => TestingQuestion::HIDDEN_NO,
                    TestingQuestionCategory::tableName() . " . [[hidden]]" => TestingQuestionCategory::HIDDEN_NO,
                ])->orderBy(
                    [
                        TestingQuestion::tableName() . '.[[position]]' => SORT_ASC,
                    ]
                )->asArray()->all();

            $rightAnswerIds = TestingQuestionAnswer::find()->select(['id'])->where([
                'right' => TestingQuestionAnswer::RIGHT_YES,
            ])->column();

            $categories = TestingQuestionCategory::find()->where([
                'hidden' => TestingQuestionCategory::HIDDEN_NO,
            ])->indexBy('id')
                ->asArray()->all();

            $data = [$questions, $rightAnswerIds, $categories];
            Yii::$app->cache->set($key, $data, null, $dependency);
        }

        list($questions, $rightAnswerIds, $categories) = $data;
        $answerModel = new TestingQuestionAnswer();

        if (Yii::$app->request->isPost &&
            ($answers = Yii::$app->request->post($answerModel->formName(), false)) !== false
        ) {
            $time = (int)Yii::$app->request->post('time', 0);
            $result = new TestingResult([
                'testId' => $id,
                'ip' => ip2long(Yii::$app->request->getUserIP()),
                'time' => $time,
            ]);
            $result->save();
            foreach ($answers as $question_id => $answer) {
                $answer_ids = ArrayHelper::getValue($answer, 'answer_id', []);
                if (!empty($answer_ids)) {
                    foreach ($answer_ids as $key => $answer_id) {
                        (new TestingResultAnswer([
                            'testId' => $id,
                            'testQuestionId' => (int)$question_id,
                            'testQuestionAnswerId' => (int)$answer_id,
                            'testResultId' => $result->id,
                            'right' => (in_array($answer_id, $rightAnswerIds) ?
                                TestingResultAnswer::RIGHT_YES :
                                TestingResultAnswer::RIGHT_NO
                            ),
                        ]))->save();
                    }
                }
            }

            return $this->redirect(['result', 'id' => $result->id]);
        }

        // randomize questions
        shuffle($questions);
        $list = [];
        foreach ($categories as $category) {
            $count = 0;
            foreach ($questions as $question) {
                if ($question['categoryId'] == $category['id'] && $count < $category['limit']) {
                    array_push($list, $question);
                    $count++;
                } elseif ($count >= $category['limit']) {
                    break;
                }
            }
        }

        return $this->render('answer', [
            'model' => $model,
            'questions' => $list,
            'answerModel' => $answerModel,
        ]);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionResult($id)
    {
        $result = TestingResult::findOne($id);
        if (!$result) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $model = $this->findModel($result->testId);

        $answerCountCategory = TestingQuestionCategory::find()->where([
            'testId' => $model->id,
        ])->sum('[[limit]]');

        $answerCountTotal = TestingQuestionAnswer::find()->where([
            'testId' => $model->id,
        ])->count();

        $answerCount = $answerCountTotal > $answerCountCategory ? $answerCountCategory : $answerCountTotal;

        $answerRightCount = TestingResultAnswer::find()->where([
            'testResultId' => $result->id,
            'right' => TestingQuestionAnswer::RIGHT_YES,
        ])->count();

        $percentRight = 0;
        if ($answerCount) {
            $percentRight = round($answerRightCount / $answerCount * 100, 1);
        }

        return $this->render('result', [
            'model' => $model,
            'result' => $result,
            'answerCount' => $answerCount,
            'answerRightCount' => $answerRightCount,
            'percentRight' => $percentRight,
        ]);
    }

    /**
     * @param $id
     *
     * @return Testing|static|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Testing::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}