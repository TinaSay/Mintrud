<?php

namespace app\modules\event\forms;


use app\modules\event\models\Accreditation;
use yii\helpers\ArrayHelper;

class AccreditationForm extends Accreditation
{

    public $verifyCode = null;

    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['verifyCode'], 'captcha', 'captchaAction' => 'events/event/captcha'];
        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event_id' => 'Event ID',
            'name' => 'Имя *',
            'surname' => 'Фамилия *',
            'middle_name' => 'Отчество *',
            'passport_series' => 'Серия *',
            'passport_number' => 'Номер *',
            'passport_burthday' => 'Дата рождения *',
            'passport_burthplace' => 'Место рождения *',
            'passport_issued' => 'Кем и когда выдан *',
            'org' => 'СМИ *',
            'job' => 'Должность *',
            'accid' => 'Номер аккредитационного удостоверения',
            'phone' => 'Контактные телефоны *',
            'email' => 'E-mail *',
            'base_formation' => 'Наименование и количество аппаратуры',
        ];
    }
}