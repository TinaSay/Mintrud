<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 21.11.17
 * Time: 17:20
 */

namespace app\modules\questionnaire\models\repositories;


use app\modules\questionnaire\models\result\Result;
use RuntimeException;

/**
 * Class ResultRepository
 * @package app\modules\questionnaire\models\repositories
 */
class ResultRepository
{
    /**
     * @param Result $model
     */
    public function delete(Result $model)
    {
        if (!$model->delete()) {
            throw new RuntimeException('Deleting error');
        }
    }
}