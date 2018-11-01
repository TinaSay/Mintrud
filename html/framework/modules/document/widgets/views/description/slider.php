<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2017
 * Time: 15:22
 */

/** @var $this \yii\web\View */
/** @var $documents \app\modules\document\models\Document[] */
/** @var $context \app\modules\document\widgets\DescriptionInterface */

$context = $this->context;

$chunk = array_chunk($documents, 5);

$class = $context->hasNews() ? 'col-md-6' : 'col-md-12';

?>

<div class="<?= $class ?>">
    <div class="slider-vert-news" id="sliderDoc">
        <div class="slider">
            <?php foreach ($chunk as $documents): ?>
                <div class="slider-vert-news__slide">
                    <?php foreach ($documents as $document): ?>
                        <div class="slider-vert-news__item">
                            <a href="<?= $document->getUrl() ?>">
                                <p class="text-black"><?= $document->title ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
