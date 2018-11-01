<?php
/** @var $this \yii\web\View */

/** @var $model \app\modules\document\models\Document */

use yii\helpers\HtmlPurifier;

?>
<h1><?= $model->title ?></h1>
<h2><?= $model->announce ?></h2>
<div>
    <?= HtmlPurifier::process($model->text, [
        'HTML.ForbiddenAttributes' => [
            '*@style',
            '*@title',
            '*@alt',
        ],
    ]) ?>
</div>
