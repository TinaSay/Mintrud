<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\modules\event\widgets\LaterWidget;
use app\modules\favorite\widgets\AddFavoriteWidget;
use app\modules\rating\widgets\RatingWidget;

/** @var $this \yii\web\View */
/** @var $model \app\modules\event\models\Event */
/** @var $accreditation \app\modules\event\models\Accreditation */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Пресс-центр', 'url' => '/news/news/list'];
$this->params['breadcrumbs'][] = ['label' => 'Мероприятия', 'url' => ['/events/event/index']];
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
    'content' => preg_replace("#([\r\n\t\s]+)#", ' ', $model->place),
], 'og:description');

?>

<section class="pd-top-0 pd-bottom-30">
    <div class="clearfix">
        <div class="main">
            <h1 class="page-title text-black"><?= $model->title ?></h1>
            <p class="page-date text-light"><?= $model->asDates() ?></p>
            <div class="post-content text-dark">
                <?= $model->text ?>
                <?php if ($model->show_form == $model::SHOW_FORM_YES): ?>
                    <a href="#" data-toggle="modal" class="btn btn-normal btn-lg btn-block btn-primary mr-top-50" data-target="#modalAccreditation">Аккредитация представителей СМИ на мероприятие</a>
                <?php endif; ?>
            </div>
        </div>
        <aside class="main-aside">
            <?= $this->render('//parts/right-side-menu') ?>
            <?= RatingWidget::widget(['module' => $model::className(), 'recordId' => $model->id]) ?>
            <div class="border-block block-arrow">
                <p class="text-light">Опубликовано на сайте:</p>
                <p class="pd-bottom-15"><?= $model->asCreatedAt() ?></p>
            </div>
        </aside>
    </div>
</section>

<section class="pd-top-30 pd-bottom-70">
    <div class="border-top"></div>
    <?= LaterWidget::widget(['except' => $model->id]) ?>
</section>

<?= $this->render('_popup', ['accreditation' => $accreditation]) ?>
