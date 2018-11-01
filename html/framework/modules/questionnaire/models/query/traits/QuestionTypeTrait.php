<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 09.07.2017
 * Time: 16:40
 */

namespace app\modules\questionnaire\models\query\traits;


use app\modules\questionnaire\models\Question;
use yii\db\ActiveQuery;

/**
 * Trait QuestionType
 * @package app\modules\questionnaire\models\query\traits
 */
trait QuestionTypeTrait
{
    /**
     * @param int $type
     * @return $this|ActiveQuery
     */
    public function type(int $type): ActiveQuery
    {
        return $this->andWhere([Question::tableName() . '.[[type]]' => $type]);
    }
}