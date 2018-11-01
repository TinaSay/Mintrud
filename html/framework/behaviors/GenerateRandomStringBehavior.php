<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 02.12.16
 * Time: 13:11
 */

namespace app\behaviors;

use Closure;
use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class GenerateRandomStringBehavior
 *
 * ```php
 * public function behaviors()
 * {
 *      return [
 *          'GenerateRandomStringBehavior' => [
 *              'class' => GenerateRandomStringBehavior::className(),
 *              'attribute' => 'token',
 *              'stringLength' => 256,
 *          ],
 *      ];
 * }
 * ```
 *
 * @package app\behaviors
 */
class GenerateRandomStringBehavior extends AttributeBehavior
{
    /**
     * @var string|null
     */
    public $attribute = null;

    /**
     * @var int
     */
    public $stringLength = 64;

    /**
     * @var bool|Closure
     */
    public $enabled = true;

    public function init()
    {
        parent::init();

        if ($this->attribute === null) {
            throw new InvalidConfigException('The "attribute" property must be set.');
        }

        if (empty($this->attributes)) {
            $this->attributes = [
                ActiveRecord::EVENT_BEFORE_INSERT => [$this->attribute],
                ActiveRecord::EVENT_BEFORE_UPDATE => [$this->attribute],
            ];
        }
    }

    /**
     * @return array
     */
    public function events()
    {
        return ArrayHelper::merge(parent::events(), [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ]);
    }

    /**
     * @param yii\base\Event $event
     */
    public function beforeValidate($event)
    {
        $this->enabled = ($this->enabled instanceof Closure ? call_user_func($this->enabled, $event) : $this->enabled);

        if ($this->enabled === false) {
            $this->attributes = [];
        }
    }

    /**
     * @param yii\base\Event $event
     *
     * @return mixed|string
     */
    protected function getValue($event)
    {
        return $this->value instanceof Closure ? call_user_func($this->value,
            $event) : Yii::$app->getSecurity()->generateRandomString($this->stringLength);
    }
}
