<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.06.17
 * Time: 14:46
 */

namespace app\core\validators;

use yii\validators\Validator;

/**
 * Class StrengthValidator
 *
 * @package app\core\validators
 */
class StrengthValidator extends Validator
{
    public function init()
    {
        parent::init();

        if ($this->message === null) {
            $this->message = 'Значение «{attribute}» должно содержать буквы и цифры.';
        }
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        $alpha = preg_match('#[[:alpha:]]#', $value);
        $digit = preg_match('#[[:digit:]]#', $value);

        if (!$alpha || !$digit) {
            $this->addError($model, $attribute, $this->message);
        }
    }
}
