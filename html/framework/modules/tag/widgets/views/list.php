<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 06.09.2017
 * Time: 14:49
 */
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $models \app\modules\tag\models\Tag[] */

?>

<div class="tags-list">
    <?php foreach ($models as $model) : ?>
        <?= Html::a($model->name, ['/tags/relation/index', 'id' => $model->id]) ?>
    <?php endforeach; ?>
</div>

