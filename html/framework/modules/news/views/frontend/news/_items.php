<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.08.17
 * Time: 11:28
 */

/** @var $models \app\modules\news\models\News[] */
/** @var $pagination \yii\data\Pagination */

?>
<?php if (!empty($models)): ?>
    <?php
    $firstModel = array_shift($models);
    if (!empty($firstModel->src)):?>
        <div class="post-list-top">
            <div class="row">
                <div class="col-md-6">
                    <img class="post_image" src="<?= $firstModel->getThumbUrl('403x272') ?>">
                </div>
                <div class="col-md-6">
                    <p class="page-date text-light"><?= $firstModel->asDate() ?></p>
                    <a class="post-name text-black" href="<?= $firstModel->getUrl() ?>"><?= $firstModel->title ?></a>
                </div>
            </div>
        </div>
    <?php else:
        array_unshift($models, $firstModel);
    endif;
    ?>
    <?php foreach ($models as $model): ?>
        <div class="post-list">
            <p class="page-date text-light"><?= $model->asDate() ?></p>
            <a class="post-name text-black" href="<?= $model->getUrl() ?>"><?= $model->title ?></a>
        </div>
    <?php endforeach; ?>
    <div class="wrap-pagination">
        <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
    </div>
<?php else: ?>
    <p>Нет новостей.</p>
<?php endif; ?>
