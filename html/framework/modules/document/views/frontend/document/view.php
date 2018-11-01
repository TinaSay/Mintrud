<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 23.07.2017
 * Time: 17:36
 */

use app\modules\document\helpers\DocumentHelper;
use app\modules\document\models\Document;
use app\modules\favorite\widgets\AddFavoriteWidget;
use app\modules\magic\widgets\MagicWidget;
use app\modules\rating\widgets\RatingWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $model \app\modules\document\models\Document */
/** @var $context \app\modules\document\controllers\frontend\DocumentController */

$context = $this->context;

$this->title = $model->title;
$this->params['breadcrumbs'] = $context->getBreadcrumbs($model->directory_id);
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
    'content' => preg_replace("#([\r\n\t\s]+)#", ' ', $model->announce),
], 'og:description');

?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <div class="post-content text-dark docs-content">
            <?= $model->announce ?>
            <?= $model->text ?>
        </div>
        <div class="clearfix"></div>
        <?= MagicWidget::widget([
            'tpl' => 'document-list',
            'options' => ['module' => $model::className(), 'record_id' => $model->id],
        ]) ?>
    </div>
    <aside class="main-aside">

        <div class="border-block block-douwnload-sm mr-top-40">
            <a href="<?= Url::to(['download', 'id' => $model->id]) ?>" class="btn btn-block btn-primary "
               download="download">Скачать документ</a>
            <div class="text-center text-black">
                <span class="format">Формат: ZIP</span>
                <span class="size">
                    Размер: <?= Yii::$app->formatter->asShortSize($context->getSize($model->id)) ?>
                </span>
            </div>
        </div>

        <div class="border-block block-arrow">
            <?php if ($model->number): ?>
                <p class="text-light">Номер документа:</p>
                <p class="pd-bottom-15"><?= $model->number ?></p>
            <?php endif; ?>
            <?php if ($model->date): ?>
                <p class="text-light">Дата подписания:</p>
                <p class="pd-bottom-15"><?= $model->asDate('php:d.m.Y') ?></p>
            <?php endif; ?>


            <?php if ($model->ministry_number): ?>
                <p class="text-light">Номер документа в Минюсте:</p>
                <p class="pd-bottom-15"><?= $model->ministry_number ?></p>
            <?php endif; ?>

            <?php if ($model->ministry_date): ?>
                <p class="text-light">Дата регистрации в Минюсте:</p>
                <p class="pd-bottom-15"><?= DocumentHelper::asMinistryDate($model) ?></p>
            <?php endif; ?>

            <?php if ($model->organByHidden): ?>
                <p class="text-light">Принявший орган:</p>
                <p class="pd-bottom-15"><?= ArrayHelper::getValue($model->organByHidden, ['title']) ?></p>
            <?php endif; ?>
            <?php if (!empty($model->directionsByHidden)): ?>
                <p class="text-light">Направления:</p>
                <p class="pd-bottom-15">
                    <?= implode(',', ArrayHelper::getColumn($model->directionsByHidden, 'title')) ?>
                </p>
            <?php endif; ?>
            <?php if ($model->typeByHidden): ?>
                <p class="text-light">Тип:</p>
                <p class="pd-bottom-15"><?= ArrayHelper::getValue($model->typeByHidden, ['title']) ?></p>
            <?php endif; ?>
            <p class="text-light">Опубликовано на сайте:</p>
            <p class="pd-bottom-15"><?= $model->asDateCreated() ?></p>


            <?php if ($model->old_document_id !== null) { ?>
                <p class="text-light">Есть старая редакция:</p>
                <p class="pd-bottom-15"><?= Html::a(Document::find()->where(['id' => $model->old_document_id])->one()->title,
                        Url::to([
                            '/docs',
                            'url_id' =>
                                $model->url_id,
                        ]), [
                            'target'
                            => '_blank',
                        ]); ?></p>
            <?php } else {
                if (!empty($newDocumentId)) { ?>
                    <p class="text-light">Есть новая редакция:</p>
                    <p class="pd-bottom-15"><?= Html::a($newDocumentId->title, Url::to([
                            '/docs',
                            'url_id' =>
                                $newDocumentId->url_id,
                        ]), [
                            'target'
                            => '_blank',
                        ]); ?></p>
                <?php }
            } ?>
        </div>

        <?= RatingWidget::widget(['module' => $model::className(), 'recordId' => $model->id]) ?>

    </aside>
</div>
