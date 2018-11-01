<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 20:18
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\news\models\News[] */
/** @var $context \app\modules\news\controllers\frontend\NewsController */
/** @var $directoryModel \app\modules\directory\models\Directory */
/** @var $pagination \yii\data\Pagination */

$this->title = $directoryModel->title;

$context = $this->context;


$this->params['breadcrumbs'] = $context->getBreadcrumbs($directoryModel->parent_id);
?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <?php if (!empty($models)): ?>
            <?php
            $fistModel = array_shift($models)
            ?>
            <div class="post-list-top">
                <div class="row">
                    <?php if ($fistModel->src): ?>
                        <div class="col-md-6">

                            <img class="post_image" src="<?= $fistModel->getThumbUrl('403x272') ?>">
                        </div>
                    <?php endif; ?>
                    <div class="col-md-<?= $fistModel->src ? '6' : '12' ?>">
                        <p class="page-date text-light"><?= $fistModel->asDate() ?></p>
                        <a class="post-name text-black" href="<?= $fistModel->getUrl() ?>"><?= $fistModel->title ?></a>
                    </div>
                </div>
            </div>

            <?php foreach ($models as $model): ?>

                <div class="post-list">
                    <p class="page-date text-light"><?= $model->asDate() ?></p>
                    <a class="post-name text-black" href="<?= $model->getUrl() ?>"><?= $model->title ?></a>
                </div>
            <?php endforeach; ?>
            <div class="wrap-pagination">
                <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
            </div>
        <?php endif; ?>
    </div>
</div>
