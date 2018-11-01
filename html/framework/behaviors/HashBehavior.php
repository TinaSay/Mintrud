<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 02.12.16
 * Time: 13:23
 */

namespace app\behaviors;

use Closure;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Class HashBehavior
 *
 * ```php
 * public function behaviors()
 * {
 *      return [
 *          'HashBehavior' => [
 *              'class' => HashBehavior::className(),
 *              'attribute' => 'password',
 *          ],
 *      ];
 * }
 * ```
 *
 * @package app\behaviors
 */
class HashBehavior extends AttributeBehavior
{
    /**
     * @var null
     */
    public $attribute = 'password';

    /**
     * @var array
     */
    public $events = [];

    /**
     * @var array
     */
    public $scenarios = [];

    /**
     * @return array
     */
    public function events()
    {
        if (empty($this->events)) {
            return [
                ActiveRecord::EVENT_BEFORE_INSERT => 'before',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'before',
            ];
        } else {
            return $this->events;
        }
    }

    /**
     * @param yii\base\Event $event
     */
    public function before($event)
    {
        $sender = $event->sender;
        if ($this->scenarios && in_array($sender->getScenario(), $this->scenarios) || empty($this->scenarios)) {
            if ($sender->{$this->attribute} === '') {
                unset($sender->{$this->attribute});
            } else {
                $hash = $this->value instanceof Closure ? call_user_func($this->value,
                    $event) : Yii::$app->getSecurity()->generatePasswordHash($sender->{$this->attribute});

                $sender->{$this->attribute} = $hash;
            }
        } else {
            unset($sender->{$this->attribute});
        }
    }
}
