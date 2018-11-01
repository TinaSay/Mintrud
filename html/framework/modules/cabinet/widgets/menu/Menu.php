<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.07.17
 * Time: 12:29
 */

namespace app\modules\cabinet\widgets\menu;

use Yii;
use yii\base\Widget;

/**
 * Class Menu
 *
 * @package app\modules\cabinet\widgets\menu
 */
class Menu extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        return $this->render('menu', [
            'active' => Yii::$app->controller->action->uniqueId,
            'user' => Yii::$app->getUser()->getIdentity(),
        ]);
    }
}
