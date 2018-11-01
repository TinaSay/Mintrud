<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;
use app\modules\favorite\widgets\AddFavoriteWidget;
use app\modules\news\widgets\NewsLaterByDirectoryWidget;
use app\modules\rating\widgets\RatingWidget;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $model \app\modules\news\models\News */
/** @var \app\modules\news\controllers\frontend\NewsController $context */

$context = $this->context;

$this->title = $model->title;

$this->params['breadcrumbs'] = array_merge(
    [['label' => 'Пресс-центр', 'url' => '/news/news/list']],
    $context->getBreadcrumbs($model->directory_id)
);
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
if ($model->src) {
    $this->registerMetaTag([
        'property' => 'og:image',
        'content' => Yii::$app->request->hostInfo . $model->getThumbUrl('836x410'),
    ], 'og:image');
}
?>
<?= $this->render('//parts/breadcrumbs') ?>

<!-- page content -->
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="clearfix">
                    <div class="main">
                        <h1 class="page-title text-black"><?= $model->title ?></h1>
                        <p class="page-date text-light"><?= $model->asDate() ?></p>
                        <?php if ($model->src): ?>
                            <img class="post_image" src="<?= $model->getThumbUrl('836x410'); ?>">
                        <?php endif; ?>
                        <div class="post-content text-dark">
                            <?= $model->text ?>
                        </div>
                    </div>
                    <aside class="main-aside">
                        <?= $this->render('//parts/right-side-menu') ?>
                        <div class="border-block block-arrow">
                            <p class="text-light">Категория:</p>
                            <p class="pd-bottom-15"><?= ArrayHelper::getValue($model->directory, 'title'); ?></p>
                        </div>
                        <div class="border-block">
                            <?= app\modules\newsletter\widgets\NewsletterShow::widget(); ?>
                        </div>
                        <?= RatingWidget::widget(['module' => $model::className(), 'recordId' => $model->id]) ?>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</section>

<?= \app\modules\media\widgets\MediaPhotoNewsWidget::widget([
    'model' => $model,
]); ?>

<!-- page content -->
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <section class="pd-top-30 pd-bottom-70">
                    <div class="border-top"></div>
                    <?= NewsLaterByDirectoryWidget::widget([
                        'directories' => $model->directory_id,
                        'except' => $model->id,
                    ]) ?>
                </section>
            </div>
        </div>
    </div>
</section>

