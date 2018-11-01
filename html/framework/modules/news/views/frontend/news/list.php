<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 20:18
 */

use app\modules\news\assets\NewsAsset;
use app\modules\news\models\News;
use yii\helpers\Url;
use app\modules\document\widgets\DescriptionWidget;

/** @var $this \yii\web\View */
/** @var $models News[] */
/** @var $pagination \yii\data\Pagination */
/** @var $widgets \app\modules\news\models\WidgetOnMain[] */
/** @var $searchModel \app\modules\news\models\search\NewsSearch */

NewsAsset::register($this);

$this->title = 'Картина дня';

$this->params['breadcrumbs'][] = ['label' => 'Пресс-центр', 'url' => '/news/news/list'];
?>
<div class="clearfix">
    <div class="main">
        <div class="tabs-narrow no-left-pd tabs-nav-wrap--column tabs-nav-wrap text-black pd-top-0 mr-bottom-60 clearfix">
            <h1 class="section-head page-title text-black pull-left"><?= $this->title ?></h1>
            <ul class="nav nav-tabs pull-right" id="day_map_tabs_nav">
                <li class="custom-tab-item active" data-content="day_map_all">
                    <a href="#day_map_all">Все</a>
                </li>
                <?php foreach ($widgets as $widget): ?>
                    <li class='custom-tab-item' data-content="day_map_<?= $widget->directory_id ?>">
                        <a href="#day_map_<?= $widget->directory_id ?>"><?= $widget->title ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>

        <div id="day_map_tabs_content">
            <div id="day_map_all"
                 data-url="<?= Url::to(['/news/news/list']); ?>"
                 data-directory="0"
                 class="custom-tabs-content active"
            >
                <?= $this->render('_items', [
                    'models' => $models,
                    'pagination' => $pagination,
                ]); ?>
            </div>
            <?php foreach ($widgets as $widget): ?>
                <div id="day_map_<?= $widget->directory_id; ?>"
                     data-url="<?= Url::to(['/news/news/list']); ?>"
                     data-directory="<?= $widget->directory_id; ?>"
                     class="custom-tabs-content">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="main-aside">
        <?= $this->render('//parts/right-side-menu') ?>
        <?= $this->render('_search', ['searchModel' => $searchModel]) ?>
        <div class="border-block">
            <?=app\modules\newsletter\widgets\NewsletterShow::widget(['nameModel' => 'news']);?>
        </div>
        <?= DescriptionWidget::widget() ?>
    </aside>
</div>
