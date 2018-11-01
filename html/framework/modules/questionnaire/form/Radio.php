<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 15:00
 */

namespace app\modules\questionnaire\form;


use yii\base\Model;

/**
 * Class Radio
 * @package app\modules\questionnaire\form
 */
class Radio extends Model
{
    /**
     * @var
     */
    public $radio;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['radio', 'each', 'rule' => ['integer']]
        ];
    }


}