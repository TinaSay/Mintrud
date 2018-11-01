<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 21.11.17
 * Time: 17:25
 */

namespace app\modules\questionnaire\models\repositories;


use app\modules\questionnaire\models\ResultAnswer;
use RuntimeException;

/**
 * Class ResultAnswerRepository
 * @package app\modules\questionnaire\models\repositories
 */
class ResultAnswerRepository
{
    /**
     * @param ResultAnswer $model
     */
    public function delete(ResultAnswer $model)
    {
        if (!$model->delete()) {
            throw new RuntimeException('Deleting error');
        }
    }

    /**
     * @param ResultAnswer $model
     */
    public function save(ResultAnswer $model)
    {
        if (!$model->save()) {
            throw new RuntimeException('Saving error');
        }
    }
}