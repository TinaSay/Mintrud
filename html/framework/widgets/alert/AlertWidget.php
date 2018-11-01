<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 12.10.15
 * Time: 14:16
 */

namespace app\widgets\alert;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\Alert;
use yii\bootstrap\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class AlertWidget
 *
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', 'This is the message');
 * \Yii::$app->session->setFlash('success', 'This is the message');
 * \Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * or
 *
 * ```php
 * \Yii::$app->getSession()->addFlash('error', 'Error 1');
 * \Yii::$app->getSession()->addFlash('error', 'Error 2');
 * ```
 *
 * @package app\modules\cp\widgets
 */
class AlertWidget extends Widget
{
    /**
     * @var array
     */
    public $alert = [
        'danger' => 'alert-danger',
        'success' => 'alert-success',
        'info' => 'alert-info',
        'warning' => 'alert-warning',
    ];

    /**
     * @var string
     */
    public $defaultAlert = 'alert-info';

    /**
     * @throws InvalidConfigException
     */
    public function run()
    {
        foreach (Yii::$app->getSession()->getAllFlashes() as $key => $data) {

            $class = ArrayHelper::getValue($this->alert, $key, $this->defaultAlert);

            $this->alert($class, $data);
        }
    }

    /**
     * @param string $class
     * @param string|array $data
     */
    protected function alert($class, $data)
    {
        if (is_array($data)) {
            foreach ($data as $row) {
                $this->alert($class, $row);
            }
        } else {
            echo Alert::widget([
                'options' => ['class' => $class],
                'body' => $data,
            ]);
        }
    }
}
