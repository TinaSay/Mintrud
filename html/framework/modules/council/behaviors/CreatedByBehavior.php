<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.11.15
 * Time: 22:16
 */

namespace app\modules\council\behaviors;

use Closure;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Class CreatedByBehavior
 *
 * ```php
 * use app\modules\council\behaviors\CreatedByBehavior;
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
    public $user = 'lk';

    /**
     * @var string
     */
    public $attribute = 'council_member_id';

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
        return $this->value instanceof Closure ? call_user_func($this->value,
            $event) : Yii::$app->get($this->user)->getId();
    }
}
