<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 27.06.17
 * Time: 14:28
 */

namespace app\modules\council\forms;

/**
 * Class ConfigExportForm
 * @package app\modules\council\forms
 *
 * @property string $email
 * @property string $period
 * @property string $subscribeTime
 */
class SettingsForm extends \yii\base\Model
{

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $period;

    /**
     * @var string
     */
    public $subscribeTime;
    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['email', 'period', 'subscribeTime'], 'required'],
            [['email', 'period', 'subscribeTime'], 'string'],
            [['email'], 'email'],
            [['period'], 'default', 'value' => '7'],
            [['period'], 'default', 'value' => '10:00'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Редактор',
            'period' => 'Период рассылки результатов голосования',
            'subscribeTime' => 'Время отправки рассылки',
        ];
    }

    /**
     * @return array
     */
    public static function getPeriodList()
    {
        return [
            '1' => 'Ежедневно',
            '7' => 'Еженедельно',
            '14' => 'Раз в 2 недели',
            '30' => 'Раз в месяц',
        ];
    }

}