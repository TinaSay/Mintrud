<?php

use app\modules\favorite\widgets\AddFavoriteWidget;
use app\modules\rating\widgets\RatingWidget;
use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $model app\modules\media\models\Video */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Пресс-центр', 'url' => '/news/news/list'];
$this->params['breadcrumbs'][] = ['label' => 'Медиафайлы', 'url' => ['/media']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['share-page'] = true;

$this->beginBlock('add-favorite');
echo AddFavoriteWidget::widget(
    [
        'addView' => 'breadcrumbs/add-favorite',
        'existView' => 'breadcrumbs/exist-favorite',
    ]
);
$this->endBlock();

?>
<div class="clearfix">
    <div class="main pd-bottom-120">
    
        <main>
            <h1 class="page-title text-black"><?= Html::encode($this->title) ?></h1>
            <p class="content-text">
                <span class="news-date"><?= \Yii::$app->formatter->asDate($model->created_at) ?></span>
            </p>
            <?php if ($model->src) : ?>
                <p class="content-text">
                    <video src="<?= $model->getDownloadUrl('src') ?>" controls></video>
                </p>
            <?php endif; ?>
            <div class="information information-video-wrap text-dark" id="description" data-atlanta="full">
                <?= $model->text ?>
            </div>
        </main>
    </div>

    <aside class="main-aside">
        <?= $this->render('//parts/right-side-menu') ?>
        <?= RatingWidget::widget(['module' => $model::className(), 'recordId' => $model->id]) ?>
    </aside>
</div>
