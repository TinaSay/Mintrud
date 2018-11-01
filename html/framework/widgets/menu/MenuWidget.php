<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 14:45
 */

namespace app\widgets\menu;

use Yii;
use yii\base\Widget;

class MenuWidget extends Widget
{
    /**
     * @var array
     */
    protected $menu = [];

    public $view = 'default';

    public function run()
    {
        $module = Yii::$app->controller->getModules();
        $module = end($module);

        if ($module && $module->id == 'lk' && !Yii::$app->get('lk')->getIsGuest()) {
            $this->view = 'lk-menu';
        }

        return $this->render($this->view);
    }
}