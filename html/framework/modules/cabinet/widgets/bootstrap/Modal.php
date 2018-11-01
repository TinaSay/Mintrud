<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.07.17
 * Time: 15:02
 */

namespace app\modules\cabinet\widgets\bootstrap;

use yii\helpers\Html;

/**
 * Class Modal
 *
 * @package app\modules\cabinet\widgets\bootstrap
 */
class Modal extends \yii\bootstrap\Modal
{
    /**
     * Initializes the widget.
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        $this->initOptions();

        echo $this->renderToggleButton() . "\n";
        echo Html::beginTag('div', $this->options) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-dialog ' . $this->size]) . "\n";
        echo Html::beginTag('div', ['class' => 'modal-content bg-gray']) . "\n";
        echo $this->renderHeader() . "\n";
        echo $this->renderBodyBegin() . "\n";
    }
}
