<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 15:59
 */

namespace app\modules\questionnaire\form;


use yii\base\Model;

/**
 * Class Checkbox
 * @package app\modules\questionnaire\form
 */
class Checkbox extends Model
{
    /**
     * @var
     */
    public $checkbox;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['checkbox', 'safe']
        ];
    }
}