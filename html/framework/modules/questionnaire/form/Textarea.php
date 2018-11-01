<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 18:19
 */

namespace app\modules\questionnaire\form;


use yii\base\Model;

/**
 * Class Textarea
 * @package app\modules\questionnaire\form
 */
class Textarea extends Model
{
    /**
     * @var
     */
    public $textarea;


    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['textarea', 'each', 'rule' => ['string']]
        ];
    }


}