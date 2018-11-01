<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 17:48
 */

namespace app\modules\questionnaire\form;


use yii\base\Model;

/**
 * Class Text
 * @package app\modules\questionnaire\form
 */
class Text extends Model
{
    /**
     * @var
     */
    public $text;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['text', 'each', 'rule' => ['string']]
        ];
    }
}