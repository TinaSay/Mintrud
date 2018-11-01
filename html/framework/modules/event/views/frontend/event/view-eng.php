<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

/** @var $this \yii\web\View */
/** @var $model \app\modules\event\models\Event */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => 'Events', 'url' => ['/events/event/index']];
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => preg_replace("#([\r\n\t\s]+)#", ' ', $model->place),
], 'og:description');

?>

<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $model->title ?></h1>
                <p class="page-date text-light"><?= $model->asDate() ?></p>
                <div class="post-content text-dark">
                    <?= $model->text ?>
                </div>
            </div>
        </div>
    </div>
</section>