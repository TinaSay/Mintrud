<?php

use app\modules\media\models\Photo;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\media\models\Photo */
?>
<div class="row">
    <?php foreach ($model->images as $key => $image): ?>
    <div style="col-md-4">
        <?= Html::img(Photo::getPreviewImage($image), ['margin' => '10px']) ?>
        <?= $image->getHint() ?>
    </div>
    <?php if (($key - 2) % 3 == 0): ?>
</div>
<div class="row">
    <?php endif; ?>
    <?php endforeach; ?>
</div>
