<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 13:00
 */


/** @var $this \yii\web\View */
/** @var $models \app\modules\news\models\News[] */

?>
<?php if (!empty($models)): ?>
    <section class="news-prime-blok pd-top-10">
        <div class="container">

            <?php $fistModel = array_shift($models) ?>
            <div class="row">
                <a href="<?= $fistModel->getUrl(); ?>" class="news-first flax-wrap">
                    <div class="col-xs-12 col-sm-12 col-md-8">
                        <div class="image-wrap image-lg"
                             style="background-image: url(<?= $fistModel->getThumbUrl('805x410'); ?>)"></div>
                    </div>
                    <div class="col-md-4">
                        <div class="news-first-text text-black">
                            <h2 class="news-title"><?= $fistModel->title ?></h2>
                            <p class="news-date text-light"><?= $fistModel->asDate(); ?></p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="row flax-wrap">
                <?php foreach ($models as $model) : ?>
                    <div class="col-md-4 col-sm-6">
                        <a class="news-card-img text-black" href="<?= $model->getUrl(); ?>">
                            <div class="card-img">
                                <div class="image-wrap image-md"
                                     style="background-image: url(<?= $model->getThumbUrl('387x200'); ?>)"></div>
                            </div>
                            <h4 class="news-title"><?= $model->title ?></h4>
                            <p class="news-date text-light"><?= $model->asDate(); ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>