<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.08.2017
 * Time: 15:06
 */

/** @var $this \yii\web\View */
/** @var $context \app\modules\document\widgets\DescriptionInterface */

$context = $this->context;

$class = $context->hasNews() ? 'col-md-6' : 'col-md-12';

?>

<div class="<?= $class ?> hidden-less-md">
    <div class="slider-vert__nav slider-doc-nav">
        <span class="slider-vert__nav-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></span>
        <span class="slider-vert__nav-next"><i class="fa fa-angle-right" aria-hidden="true"></i></span>
    </div>
</div>
