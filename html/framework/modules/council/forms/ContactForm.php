<?php


namespace app\modules\council\forms;


class ContactForm extends \yii\base\Model
{
    /**
     * @var string
     */
    public $contact;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['contact', 'string']
        ];
    }
}