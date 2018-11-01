<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.06.17
 * Time: 18:09
 */

namespace app\core\validators;

use yii\validators\Validator;

/**
 * Class PasswordValidator
 *
 * @package app\core\validators
 */
class PasswordValidator extends Validator
{
    /**
     * @var bool
     */
    public $skipOnEmpty = false;

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     */
    public function validateAttribute($model, $attribute)
    {
        if ($model->$attribute === '') {
            unset($model->$attribute);
        }
    }
}
