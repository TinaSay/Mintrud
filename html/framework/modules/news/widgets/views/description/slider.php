<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 13:15
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\news\models\News[] */
/** @var $context \app\modules\news\widgets\DescriptionInterface */

$context = $this->context;

$chunk = array_chunk($models, 5);

$class = $context->hasDocument() ? 'col-md-6' : 'col-md-12';

?>
<div class="<?= $class ?>">
    <div class="slider-vert-news" id="sliderMaterials">
        <div class="slider">
            <?php foreach ($chunk as $news): ?>
                <div class="slider-vert-news__slide">
                    <?php foreach ($news as $model): ?>
                        <?php /** @var $model \app\modules\news\models\News */ ?>
                        <div class="slider-vert-news__item">
                            <a href="<?= $model->getUrl() ?>">
                                <span class="date"><?= $model->asDate() ?></span>
                                <p class="text-black"><?= $model->title ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
