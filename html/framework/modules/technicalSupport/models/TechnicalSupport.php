<?php

namespace app\modules\technicalSupport\models;

//use app\components\HtmlPurifierValidator;
use yii\base\Model;

/**
 * Class TechnicalSupport
 *
 * @package app\modules\technicalSupport\models
 */
class TechnicalSupport extends Model
{
    /**
     * @var string
     */
    public $theme = '';

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var string
     */
    public $email = '';

    /**
     * @var string
     */
    public $phone = '';

    /**
     * @var string
     */
    public $comment = '';

    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @var int
     */
    public $deal;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name', 'email', 'comment', 'deal'], 'required'],
            [['name', 'theme', 'phone'], 'string'],
            ['email', 'email'],
            [['comment'], 'string', 'max' => 3728],
            [
                ['comment'],
                'filter',
                'filter' => function ($value) {
                    return nl2br(strip_tags($value));
                },
            ],
            ['verifyCode', 'captcha', 'captchaAction' => 'technicalSupport/default/captcha'],
            [
                [
                    'deal',
                ],
                'required',
                'message' => 'Подтвердите, что ознакомлены с порядком приема обращения',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'theme' => 'Тема обращения',
            'name' => 'Имя *',
            'email' => 'E-mail *',
            'phone' => 'Телефон',
            'comment' => 'Сообщение *',
            'verifyCode' => 'Введите код с картинки *',
            'deal' => 'Разрешаю обработку персональных данных',
        ];
    }
}
