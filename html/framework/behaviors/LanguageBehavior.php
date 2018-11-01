<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.08.15
 * Time: 23:19
 */

namespace app\behaviors;

use Closure;
use Opis\Closure\SerializableClosure;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

/**
 * Class LanguageBehavior
 *
 * ```php
 * use app\behaviors\LanguageBehavior;
 *
 * public function behaviors()
 * {
 *     return [
 *         'LanguageBehavior' => [
 *             'class' => LanguageBehavior::className(),
 *             'attribute' => 'language',
 *             'value' => function($event){},
 *         ],
 *     ];
 * }
 * ```
 *
 * @package behaviors
 */
class LanguageBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $attribute = 'language';

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
     * @param yii\base\Event $event
     *
     * @return mixed|string
     */
    protected function getValue($event)
    {
        return ($this->value instanceof Closure || $this->value instanceof SerializableClosure) ? call_user_func($this->value, $event) : Yii::$app->language;
    }
}
