<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 17:41
 */
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $models \app\modules\document\models\DescriptionDirectory[] */

?>
<ul class="list-nav">
    <?php foreach ($models as $model): ?>
        <li class="">
            <a class="text-dark"
               href="<?= $model->getUrl() ?>"><?= ArrayHelper::getValue($model, ['directory', 'title']) ?></a>
        </li>
    <?php endforeach; ?>
</ul>
