<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:45
 */

use app\modules\document\assets\AnimationNumberAsset;
use app\modules\document\widgets\ButtonSliderDirectionWidget;
use app\modules\document\widgets\DirectionCountWidget;
use app\modules\document\widgets\DirectionListWidget;
use app\modules\document\widgets\DocDirectionWidget;
use app\modules\document\widgets\DocumentCountDescriptionWidget;
use app\modules\news\widgets\ButtonSliderDirectionWidget as ButtonSliderNewsDirectionWidget;
use app\modules\news\widgets\NewsCountDescriptionWidget;
use app\modules\news\widgets\NewsDirectionWidget;

/** @var $this \yii\web\View */
/** @var $direction \app\modules\document\models\Direction */
/** @var $description \app\modules\document\models\DescriptionDirectory */
/** @var $context \app\modules\document\controllers\frontend\DirectionController */
/** @var $dependency \yii\caching\TagDependency */

$context = $this->context;
$this->title = $direction->directory->title;

$breadcrumbs = $context->getBreadcrumbs($direction->directory->id);
array_pop($breadcrumbs);
$this->params['breadcrumbs'] = array_merge($breadcrumbs);


AnimationNumberAsset::register($this);

?>

<!-- HEADER -->
<div class="header-wrap">
    <?= $this->render('//parts/top-header'); ?>
    <div class="theme-header" style="background-image: url(/static/default/img/image/theme_bg_1.jpg);">
        <div class="theme-header__inner">
            <?= $this->render('//parts/menu') ?>
            <!-- breadcrumb -->
            <?= $this->render('/parts/breadcrumbs') ?>
            <!-- breadcrumb end -->
            <section class="pd-top-0 pd-bottom-30">
                <div class="container">
                    <div class="clearfix">
                        <div class="main">
                            <h1 class="page-title"><?= $this->title ?></h1>
                            <div class="post-content post-content--theme">
                                <?= $description->text ?>
                            </div>
                        </div>
                        <div class="main-aside">
                            <div class="border-block border-block--avatar-top">
                                <div class="avatar-top"><img src="/static/default/img/image/avatar.jpg" alt=""/></div>
                                <p>Министр Максим Топилин принял участие в совещании у Президента России Владимира
                                    Путина по ликвидации последствий паводков и пожаров</p>
                                <div class="border-block__date-light">15 мая 2017 года</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<?php if ($this->beginCache(
    __FILE__ . __LINE__ . $direction->id,
    [
        'duration' => null,
        'dependency' => $dependency,
        'variations' => [
            Yii::$app->language,
        ]
    ]
)): ?>
    <!-- HEADER END -->
    <section class="section-theme-num">
        <div class="container">
            <div class="row">
                <?= NewsCountDescriptionWidget::widget(['description' => $description]) ?>
                <?= DocumentCountDescriptionWidget::widget(['description' => $description]) ?>
                <?= DirectionCountWidget::widget(['description' => $description]) ?>
            </div>
        </div>
    </section

            <!-- page content -->
    <section class="pd-top-0 pd-bottom-80">
        <div class="container">
            <div class="clearfix">
                <div class="main">
                    <div class="row">
                        <div class="two-slider">
                            <?= NewsDirectionWidget::widget(['direction' => $direction]) ?>
                            <?= DocDirectionWidget::widget(['direction' => $direction]) ?>
                            <div class="clearfix"></div>
                            <?= ButtonSliderNewsDirectionWidget::widget(['direction' => $direction]) ?>
                            <?= ButtonSliderDirectionWidget::widget(['direction' => $direction]) ?>
                        </div>
                    </div>
                </div>
                <aside class="main-aside mr-top-0">
                    <?= DirectionListWidget::widget([
                        'description' => $description,
                        'active' => $direction->id
                    ]) ?>
                </aside>
            </div>
        </div>
    </section>
    <?php $this->endCache() ?>
<?php endif; ?>
<!-- page content end -->