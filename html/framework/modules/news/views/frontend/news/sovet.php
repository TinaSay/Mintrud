<?php

use app\modules\news\models\News;

/** @var $this \yii\web\View */
/** @var $models News[] */
/** @var $showAll boolean */
/** @var $lastUpdated integer */
/** @var $formModel \app\modules\news\forms\CommentForm */

$this->title = 'Общественный совет';

$this->params['breadcrumbs'][] = ['label' => 'Общественный совет', 'url' => '/news/news/sovet'];
$this->params['share-page'] = true;

?>
<div class="clearfix">
    <div class="main">
        <?= $this->render('_sovet_items', ['models' => $models, 'showAll' => $showAll]) ?>
    </div>
    <aside class="main-aside">
        <div class="border-block block-arrow">
            <p class="text-light">Дата обновления:</p>
            <p class="pd-bottom-15"><?= Yii::$app->formatter->asDate($lastUpdated); ?></p>
        </div>
        <?= \app\modules\ministry\widgets\MinistryMenuWidget::widget(); ?>
        <?= \app\modules\config\helpers\Config::getValue('sovet_contact_information'); ?>
        <div class="border-block">
            <h4 class="text-uppercase text-prime pd-bottom-10">Ваш вопрос <br> или предложение</h4>
            <p class="text-black pd-bottom-20">
                Задать вопрос или отправить предложенияе касающееся деятельности Общественного совета при Минтруде России
            </p>
            <a href="#" data-toggle="modal" data-target="#modalQuestion" class="btn btn-block btn-primary">Написать</a>
        </div>
        <div class="border-block border-block--blue aside-tw text-white">
            <div class="aside-tw__text">Открытая дискуссионная площадка для обсуждения предложений и заключений Общественного совета в аккаунтах Минтруда России в социальных сетях</div>
            <div class="aside-tw__bottom">
                <a href="https://twitter.com/MintrudRF" class="aside-tw-link" target="_blank"><i class="aside-tw__icon"></i> Перейти в Twitter <i class="fa fa-angle-right" aria-hidden="true"></i></a>
            </div>
        </div>
    </aside>
</div>

<?= $this->render('_popup', ['formModel' => $formModel]); ?>