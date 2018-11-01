<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 20.10.17
 * Time: 13:29
 */

declare(strict_types=1);

namespace app\modules\questionnaire\models\repositories\frontend;


use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\models\ResultAnswer;
use yii\caching\TagDependency;

/**
 * Class ResultAnswerRepository
 * @package app\modules\questionnaire\models\repositories\frontend
 */
class ResultAnswerRepository
{
    /**
     * @param int $questionId
     * @param int $answerId
     * @return int
     */
    public function countByQuestionAndAnswer(int $questionId, int $answerId): int
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $questionId,
            $answerId
        ];

        $dependency = new TagDependency([
            'tags' => [
                Result::class,
                ResultAnswer::class,
                Question::class,
                Answer::class,
                \app\modules\questionnaire\models\result\Result::class,
            ]
        ]);

        return \Yii::$app->cache->getOrSet(
            $key,
            function () use ($questionId, $answerId) {
                return (int)ResultAnswer::find()->question($questionId)->answer($answerId)->count();
            },
            null,
            $dependency
        );
    }
}