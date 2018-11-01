<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.11.15
 * Time: 22:16
 */

namespace app\behaviors;

use Closure;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\console\Application;
use yii\db\ActiveRecord;

/**
 * Class CreatedByBehavior
 *
 * ```php
 * use app\behaviors\CreatedByBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'CreatedByBehavior' => [
 *             'class' => CreatedByBehavior::className(),
 *             'attribute' => 'created_by',
 *             'value' => function($event){},
 *         ],
 *     ];
 * }
 * ```
 *
 * @package behaviors
 */
class CreatedByBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $user = 'user';

    /**
     * @var string
     */
    public $attribute = 'created_by';

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
        if (Yii::$app instanceof Application) {
            return null;
        }

        return $this->value instanceof Closure ? call_user_func($this->value,
            $event) : Yii::$app->get($this->user)->getId();
    }
}
