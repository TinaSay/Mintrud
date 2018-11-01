<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 21.11.17
 * Time: 17:26
 */

namespace app\modules\questionnaire\models\repositories;

use app\modules\questionnaire\models\ResultAnswerText;
use RuntimeException;

/**
 * Class ResultAnswerTextRepository
 * @package app\modules\questionnaire\models\repositories
 */
class ResultAnswerTextRepository
{
    /**
     * @param ResultAnswerText $model
     */
    public function delete(ResultAnswerText $model)
    {
        if (!$model->delete()) {
            throw new RuntimeException('Deleting error');
        }
    }
}