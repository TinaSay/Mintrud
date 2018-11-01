<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.01.16
 * Time: 11:32
 */

namespace app\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

/**
 * Light version!
 *
 * Class SlugBehavior
 *
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'SlugBehavior' => [
 *             'class' => SlugBehavior::className(),
 *             'attribute' => 'title',
 *             'slugAttribute' => 'slug',
 *         ],
 *     ];
 * }
 * ```
 *
 * @package behaviors
 */
class SlugBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $attribute = 'title';

    /**
     * @var string
     */
    public $slugAttribute = 'slug';

    /**
     * @var string
     */
    public $replacement = '_';

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                ActiveRecord::EVENT_BEFORE_INSERT => [$this->slugAttribute],
                ActiveRecord::EVENT_BEFORE_UPDATE => [$this->slugAttribute],
            ];
        }
    }

    /**
     * @param \yii\base\Event $event
     *
     * @return string
     */
    protected function getValue($event)
    {
        return Inflector::slug(ArrayHelper::getValue($event->sender->toArray(), $this->attribute, ''),
            $this->replacement);
    }
}
