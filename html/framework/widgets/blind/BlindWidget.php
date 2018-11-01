<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 16:25
 */

namespace app\widgets\blind;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class BlindWidget
 *
 * @package app\widgets
 */
class BlindWidget extends Widget
{
    /**
     * @return string
     */
    public function run()
    {
        $attributes = $this->getConfigure();

        return Html::renderTagAttributes($attributes);
    }

    /**
     * @return array
     */
    protected function getConfigure()
    {
        $attributes = [];
        $cookie = Yii::$app->getRequest()->getCookies();

        if ($cookie->has('blind-fontSize')) {
            $attributes['font-size'] = $cookie->get('blind-fontSize');
        }

        if ($cookie->has('blind-colorSchema')) {
            $attributes['color-schema'] = $cookie->get('blind-colorSchema');
        }

        return $attributes;
    }
}
