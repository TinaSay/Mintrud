<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 09.08.2017
 * Time: 14:37
 */

declare(strict_types=1);


namespace app\modules\questionnaire\models\repositories;


use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\ResultAnswer;
use app\modules\questionnaire\models\Type;
use yii\db\Expression;
use yii\db\Query;
use yii\web\NotFoundHttpException;

/**
 * Class QuestionRepository
 *
 * @package app\modules\questionnaire\models\repositories
 */
class QuestionRepository
{
    /**
     * @param int $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function findByQuestionnaire(int $id): array
    {
        $models = Question::find()
            ->questionnaire((int)$id)
            ->orderByPosition()
            ->hidden()
            ->all();

        if (empty($models)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        return $models;
    }

    /**
     * @param int $id
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function findByQuestionnaireWithAnswersStat(int $id): array
    {
        $models = (new Query())
            ->from(Question::tableName())
            ->select([
                Question::tableName() . '.[[id]]',
                Question::tableName() . '.[[title]]',
                Question::tableName() . '.[[type]]',
                Answer::tableName() . '.[[id]] as [[answer_id]]',
                Answer::tableName() . '.[[title]] as [[answer_title]]',
                new Expression('COUNT(' . ResultAnswer::tableName() . '.[[id]]) as [[results]]'),
            ])
            ->innerJoin(Answer::tableName(), [
                Answer::tableName() . '.[[question_id]]' => new Expression(Question::tableName() . '.[[id]]'),
            ])
            ->leftJoin(ResultAnswer::tableName(), [
                ResultAnswer::tableName() . '.[[answer_id]]' => new Expression(Answer::tableName() . '.[[id]]'),
            ])
            ->orderBy([
                Question::tableName() . '.[[position]]' => SORT_ASC,
                Answer::tableName() . '.[[position]]' => SORT_ASC,
            ])
            ->groupBy([
                Answer::tableName() . '.[[id]]',
            ])
            ->where([
                Question::tableName() . '.[[questionnaire_id]]' => $id,
                Question::tableName() . '.[[hidden]]' => Question::HIDDEN_NO,
                Question::tableName() . '.[[type]]' => Type::TYPE_ID_RADIO,
            ])
            ->all();

//            print $models->createCommand()->getRawSql();exit;
        if (empty($models)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        return $models;
    }
}