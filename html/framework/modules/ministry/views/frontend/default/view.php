<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;
use app\modules\favorite\widgets\AddFavoriteWidget;
use app\modules\ministry\widgets\MinistryMenuWidget;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $model \app\modules\ministry\models\Ministry */
/** @var \app\modules\news\controllers\frontend\NewsController $context */


$this->title = $model->title;

$this->params['breadcrumbs'] = $breadcrumbs;

$this->params['share-page'] = true;

$this->beginBlock('add-favorite');
echo AddFavoriteWidget::widget(
    [
        'addView' => 'breadcrumbs/add-favorite',
        'existView' => 'breadcrumbs/exist-favorite',
    ]
);
$this->endBlock();

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->text), 255),
], 'og:description');

?>
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $model->title ?></h1>
                <p class="page-date text-light"><?= $model->asDateCreated() ?></p>
                <div class="post-content text-dark ministry-post-content">
                    <?= $model->text ?>
                </div>
            </div>
            <aside class="main-aside">
                <?php $directories = $model->getAllDirectories() ?>
                <div class="border-block block-arrow">
                    <?php if (!empty($directories)): ?>
                        <p class="text-light">Направление деятельности:</p>
                        <p class="pd-bottom-15"><?= implode(',',
                                ArrayHelper::getColumn($directories, 'title', [])) ?></p>
                    <?php endif; ?>
                    <p class="text-light">Дата обновления:</p>
                    <p class="pd-bottom-15"><?= $model->asDateUpdated() ?></p>
                </div>
                <?php if ($model->url == 'ministry/about/structure/advisory_coordinating'): ?>
                    <?= $this->render('//parts/aside-menu') ?>
                <?php else: ?>
                    <?= MinistryMenuWidget::widget(); ?>
                <?php endif; ?>
            </aside>
        </div>
