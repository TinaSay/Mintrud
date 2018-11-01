<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 14:28
 */

namespace app\modules\reception\form;

/**
 * Class ConfigExportForm
 * @package app\modules\reception\form
 *
 * @property string $textBefore
 * @property string $textRight
 */
class SettingsForm extends \yii\base\Model
{

    /**
     * @var string
     */
    public $textBefore;

    /**
     * @var string
     */
    public $textRight;

    /**
     * @var int
     */
    public $debug;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['textBefore', 'textRight'], 'string'],
            ['debug', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'textBefore' => 'Текст перед формой обращения',
            'textRight' => 'Текст c контактами для формы обращений',
            'debug' => 'Сохранять логи и файлы обращений',
        ];
    }
}