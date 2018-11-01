<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 19.10.17
 * Time: 15:09
 */

declare(strict_types=1);

namespace app\modules\questionnaire\models\repositories\frontend;


use app\modules\questionnaire\models\Result;
use yii\caching\TagDependency;

/**
 * Class ResultRepository
 * @package app\modules\questionnaire\models\repositories\frontend
 */
class ResultRepository
{
    /**
     * @param int $questionnaireId
     * @param int $ip
     * @return bool
     */
    public function existsByQuestionnaireAndIp(int $questionnaireId, int $ip)
    {
        $exists = Result::find()
            ->questionnaire($questionnaireId)
            ->ip($ip)
            ->exists();

        return $exists;
    }


    /**
     * @param int $id
     * @return int
     */
    public function countByQuestionnaire(int $id): int
    {
        $key = [
            __CLASS__,
            __LINE__,
            __METHOD__,
            $id
        ];


        $dependency = new TagDependency([
            'tags' => [
                Result::class,
                \app\modules\questionnaire\models\result\Result::class,
            ]
        ]);

        return \Yii::$app->cache->getOrSet($key, function () use ($id) {
            return (int)Result::find()->questionnaire($id)->count();
        }, null, $dependency);
    }

    /**
     * @param Result $model
     */
    public function delete(Result $model)
    {
        if (!$model->delete()) {
            throw new \RuntimeException('deleting error');
        }
    }


    /**
     * @param Result $model
     * @param bool $runValidation
     */
    public function save(Result $model, $runValidation = true)
    {
        if (!$model->save($runValidation)) {
            throw new \RuntimeException('Saving error');
        }
    }
}