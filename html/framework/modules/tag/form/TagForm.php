<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.07.2017
 * Time: 19:59
 */

declare(strict_types = 1);

namespace app\modules\tag\form;


use app\modules\tag\models\Tag;
use yii\base\Model;

/**
 * Class TagForm
 * @package app\modules\tag\form
 */
class TagForm extends Model
{
    /**
     * @var
     */
    public $name;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 256],
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название',
        ];
    }


    /**
     * @return null|Tag
     */
    public function exist(): ?Tag
    {
        return Tag::find()->name($this->name)->one();
    }
}