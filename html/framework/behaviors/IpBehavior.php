<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 16.11.15
 * Time: 14:05
 */

namespace app\behaviors;

use Closure;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Class IpBehavior
 *
 * ```php
 * use app\behaviors\IpBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'IpBehavior' => [
 *             'class' => IpBehavior::className(),
 *             'attribute' => 'ip',
 *             'value' => function($event){},
 *         ],
 *     ];
 * }
 * ```
 *
 * @package behaviors
 */
class IpBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $attribute = 'ip';

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                ActiveRecord::EVENT_BEFORE_INSERT => [$this->attribute],
            ];
        }
    }

    /**
     * @param \yii\base\Event $event
     *
     * @return mixed|string
     */
    protected function getValue($event)
    {
        return $this->value instanceof Closure ? call_user_func($this->value, $event) : ip2long(
            Yii::$app->getRequest()->getUserIP()
        );
    }
}
