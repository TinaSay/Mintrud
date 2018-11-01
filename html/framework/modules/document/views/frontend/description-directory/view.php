<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:45
 */
use app\modules\document\assets\AnimationNumberAsset;
use app\modules\document\widgets\ButtonDocSliderDescriptionWidget;
use app\modules\document\widgets\DirectionCountWidget;
use app\modules\document\widgets\DirectionListWidget;
use app\modules\document\widgets\DocDescriptionWidget;
use app\modules\document\widgets\DocumentCountDescriptionWidget;
use app\modules\news\widgets\ButtonSliderDescriptionWidget;
use app\modules\news\widgets\NewsCountDescriptionWidget;
use app\modules\news\widgets\NewsDescriptionWidget;

/** @var $this \yii\web\View */
/** @var $directory \app\modules\directory\models\Directory */
/** @var $description \app\modules\document\models\DescriptionDirectory */
/** @var $dependency \yii\caching\TagDependency */

$this->title = $directory->title;

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
                            <div class="post-content post-content--theme" style="display: none;">
                                <?= $description->text ?>
                            </div>
                        </div>
                        <div class="main-aside">
                            <div class="border-block border-block--avatar-top">
                                <div class="avatar-top"><img src="/static/default/img/image/avatar.jpg" alt=""/></div>
                                <a href="/labour/relationship/287"><p>Минтруд России подготовил предложения о переносе выходных дней в 2018 году</p>
                                <div class="border-block__date-light">20 июня 2017</div></a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>

<?php if ($this->beginCache(
    __FILE__ . __LINE__ . $description->id,
    [
        'dependency' => $dependency,
        'duration' => null,
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
    </section>

    <!-- page content -->
    <section class="pd-top-0 pd-bottom-80">
        <div class="container">
            <div class="clearfix">
                <div class="main">
                    <div class="row">
                        <div class="two-slider">
                            <?= NewsDescriptionWidget::widget(['description' => $description]) ?>
                            <?= DocDescriptionWidget::widget(['description' => $description]) ?>
                            <div class="clearfix"></div>
                            <?= ButtonSliderDescriptionWidget::widget(['description' => $description]) ?>
                            <?= ButtonDocSliderDescriptionWidget::widget(['description' => $description]) ?>
                        </div>
                    </div>
                </div>
                <aside class="main-aside mr-top-0">
                    <?= DirectionListWidget::widget(['description' => $description]) ?>
                </aside>
            </div>
        </div>
    </section>
    <?php $this->endCache() ?>
<?php endif ?>
<!-- page content end -->