<?php
namespace app\modules\spelling\models;

//use app\components\HtmlPurifierValidator;
use yii\base\Model;

class Spelling extends Model
{
    public $url;
    public $selectedText;
    public $comment;

    public function rules()
    {
        return [
            [['url', 'selectedText', 'comment'], 'required'],
            ['url', 'url'],
            [['comment', 'selectedText'], 'filter', 'filter' => function ($value) {
                return \yii\helpers\HtmlPurifier::process($value);
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'url' => 'Ссылка',
            'selectedText' => 'Текст с ошибкой',
            'comment' => 'Ваш комментарий',
        ];
    }
}
