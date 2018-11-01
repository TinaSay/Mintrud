<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 17:25
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\directory\models\Directory[] */
/** @var \app\modules\directory\widgets\CategoryNewsWidget $context */

$context = $this->context;

?>

<ul class="list-nav">
    <?php foreach ($models as $model): ?>
        <li <?= $context->getActive() === $model->id ? 'class="active"' : null; ?>><a
                    href="<?= $model->asUrl(); ?>" class="text-dark"><?= $model->title ?></a></li>
    <?php endforeach; ?>

</ul>