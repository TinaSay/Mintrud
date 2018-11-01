<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 16:43
 */

namespace app\modules\staticVote\controllers\frontend;

use app\modules\staticVote\models\StaticVoteAnswers;
use app\modules\staticVote\models\StaticVoteQuestion;
use app\modules\staticVote\models\StaticVoteQuestionnaire;
use Yii;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
{
    public $layout = false;

    public $enableCsrfValidation = false;

    /**
     * @return string
     */
    public function actionIndex()
    {
        list($model, $questions, $answered) = $this->getQuestionnaire('labour');

        return $this->render('index', [
            'model' => $model,
            'questions' => $questions,
            'answered' => $answered,
        ]);
    }

    /**
     * @return string
     */
    public function actionChildren()
    {
        list($model, $questions, $answered) = $this->getQuestionnaire('children');

        return $this->render('index', [
            'model' => $model,
            'questions' => $questions,
            'answered' => $answered,
        ]);
    }

    /**
     * @return string
     */
    public function actionSafety()
    {
        list($model, $questions, $answered) = $this->getQuestionnaire('safety');

        return $this->render('index', [
            'model' => $model,
            'questions' => $questions,
            'answered' => $answered,
        ]);
    }

    /**
     * @param $id
     *
     * @return \yii\web\Response
     */
    public function actionAnswer($id)
    {
        $model = $this->findModel($id);
        $answerModel = new StaticVoteAnswers(
            [
                'questionnaire_id' => $model->id,
            ]
        );
        if ($answerModel->load(Yii::$app->request->post(), 'Answer')) {
            $answerModel->save();
            if ($errors = $answerModel->getErrors()) {
                Yii::$app->session->setFlash('danger', $errors);
            } else {
                Yii::$app->session->addFlash('success', 'Спасибо за Ваш ответ');
            }
        }

        $link = ($model->alias == 'labour' ? 'index' : $model->alias);

        return $this->redirect([$link]);
    }

    /**
     * @param $alias
     *
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getQuestionnaire($alias)
    {
        $model = StaticVoteQuestionnaire::find()->select([
            StaticVoteQuestionnaire::tableName() . '.[[id]]',
            StaticVoteQuestionnaire::tableName() . '.[[title]]',
            StaticVoteQuestionnaire::tableName() . '.[[text]]',
            new Expression('COUNT(' . StaticVoteAnswers::tableName() . '.[[id]]) as [[answers]]'),
        ])->joinWith('answers', false)
            ->groupBy([StaticVoteQuestionnaire::tableName() . '.[[id]]'])
            ->orderBy([StaticVoteQuestionnaire::tableName() . '.[[id]]' => SORT_ASC])
            ->limit(1)
            ->where([
                StaticVoteQuestionnaire::tableName() . '.[[hidden]]' => StaticVoteQuestionnaire::HIDDEN_NO,
                StaticVoteQuestionnaire::tableName() . '.[[alias]]' => $alias,
            ])->asArray()
            ->one();
        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        $questions = StaticVoteQuestion::find()->where([
            'questionnaire_id' => $model['id'],
        ])->orderBy(['id' => SORT_ASC])->all();

        $answered = StaticVoteAnswers::find()->where([
            'ip' => ip2long(Yii::$app->getRequest()->getUserIP()),
        ])->exists();

        return [$model, $questions, $answered];
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