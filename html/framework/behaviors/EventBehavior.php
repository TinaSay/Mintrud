<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 26.12.16
 * Time: 11:22
 */

namespace app\behaviors;

use yii\base\Behavior;

/**
 * Class EventBehavior
 *
 * Use
 *
 * ```php
 * use app\behaviors\EventBehavior;
 *
 * public function behaviors()
 * {
 *      return [
 *          'EventBehavior' => [
 *              'class' => EventBehavior::className(),
 *              'events' => [
 *                  self::EVENT_AFTER_INSERT => [self::className(), 'send'],
 *              ],
 *          ],
 *      ];
 * }
 *
 * ```
 *
 * Model
 *
 * ```php
 *
 * public static function send($event)
 * {
 *      return Yii::$app
 *      ->getMailer()
 *      ->compose('@app/mail/register.php', [
 *          'model' => $event->sender,
 *      ])
 *      ->setSubject('Регистрация в Личном кабинете')
 *      ->setFrom(Yii::$app->params['systemEmail'])
 *      ->setTo($event->sender->email)
 *      ->send();
 * }
 *
 * ```
 *
 * @package app\behaviors
 */
class EventBehavior extends Behavior
{
    /**
     * @var array
     */
    public $events = [];

    /**
     * @return array
     */
    public function events()
    {
        if (empty($this->events)) {
            return parent::events();
        } else {
            return $this->events;
        }
    }
}
