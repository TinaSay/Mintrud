<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.08.15
 * Time: 23:13
 */

namespace app\behaviors;

use DateTime;

/**
 * Class TimestampBehavior
 *
 * ```php
 * use yii\db\Expression;
 *
 * public function behaviors()
 * {
 *     return [
 *         'TimestampBehavior' => [
 *             'class' => TimestampBehavior::className(),
 *             'createdAtAttribute' => 'created_at',
 *             'updatedAtAttribute' => 'updated_at',
 *             'value' => new Expression('NOW()'),
 *         ],
 *     ];
 * }
 * ```
 *
 * @package behaviors
 */
class TimestampBehavior extends \yii\behaviors\TimestampBehavior
{
    /**
     * @param \yii\base\Event $event
     *
     * @return string
     */
    protected function getValue($event)
    {
        return $this->value ?: (new DateTime())->format('Y-m-d H:i:s');
    }
}
