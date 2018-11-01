<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 17:08
 */

namespace app\modules\questionnaire\form;


use yii\base\Model;

/**
 * Class Select
 * @package app\modules\questionnaire\form
 */
class SelectMultiple extends Model
{
    /**
     * @var
     */
    public $select;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['select', 'safe'],
        ];
    }
}