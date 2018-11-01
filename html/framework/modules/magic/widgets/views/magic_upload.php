<?php

/* @var $this yii\web\View */
/* @var $model \yii\db\ActiveRecord */
/* @var $attribute string */
/* @var $options [] */
/* @var $list [] */
/* @var $id int */

use app\assets\FancyBoxAsset;
use yii\helpers\Html;
use yii\helpers\Url;

// sortable
$url = Url::to(['/magic/manage/update']);

FancyBoxAsset::register($this);

?>
<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('magic', 'Download file') ?></div>
    <div class="panel-body">
        <?= Html::fileInput(Html::getInputName($model, $attribute) . '[]', null, $options) ?>
    </div>
</div>
