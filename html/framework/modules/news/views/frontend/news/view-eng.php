<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;

/** @var $this \yii\web\View */
/** @var $model \app\modules\news\models\News */
/** @var \app\modules\news\controllers\frontend\NewsController $context */

$context = $this->context;

$this->title = $model->title;

$this->params['breadcrumbs'] = $context->getBreadcrumbs($model->directory_id);
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
                </div>
            </div>
        </div>
    </div>
</section>
