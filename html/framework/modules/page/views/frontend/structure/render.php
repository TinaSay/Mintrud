<?php
/**
 * @var $this yii\web\View
 * @var $model \app\modules\page\models\Structure;
 * @var $subdivisions \app\modules\subdivision\models\Subdivision[]
 */

use app\components\helpers\StringHelper;
use yii\helpers\Html;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'О министерстве', 'url' => '/ministry/about'];
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->text), 255),
], 'og:description');

?>
<div class="row clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= Html::encode($model->title) ?></h1>

        <div class="pd-top-10">

            <?= $model->text ?>

            <?php /* if ($subdivisions) { ?>
                <?php foreach ($subdivisions as $subdivision) { ?>
                    <?php if ($subdivision->pages) { ?>
                        <div class="structure-box-top__top-title text-black">
                            <?= Html::encode($subdivision->title) ?>
                        </div>
                        <div class="structure-box-list structure-box-list--simple">
                            <?php foreach ($subdivision->pages as $page) { ?>
                                <div class="structure-box-top">
                                    <div class="structure-box-top__text">
                                        <a href="<?= Url::to([
                                            '/page/page/render',
                                            'subdivision' => $subdivision->fragment,
                                            'page' => $page->alias,
                                        ]) ?>" class="name text-prime">
                                            <?= Html::encode($page->getFullName()) ?>
                                        </a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                <?php } ?>
            <?php } */ ?>

            <div class="structure-box-top__top-title text-black">Совещательные и координационные органы</div>
            <div class="structure-box-list structure-box-list--simple">
                <div class="structure-box-top">
                    <div class="structure-box-top__text">
                        <p class="name lh-normal">Министерство труда и социальной защиты Российской Федерации не имеет
                            территориальных органов</p>
                    </div>
                </div>
            </div>
            <div class="structure-box-top__top-title text-black">Представительства за рубежом</div>
            <div class="structure-box-list structure-box-list--simple">
                <div class="structure-box-top">
                    <div class="structure-box-top__text">
                        <p class="name lh-normal">Министерство труда и социальной защиты Российской Федерации не имеет
                            территориальных органов</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <aside class="main-aside">
        <?= $this->render('//parts/aside-menu') ?>
    </aside>
</div>
