<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var $types array */
/** @var $searchModel \app\modules\media\models\search\MediaSearch */

$this->title = 'Медиафайлы';
$this->params['breadcrumbs'][] = ['label' => 'Пресс-центр', 'url' => '/news/news/list'];

\app\modules\media\assets\MediaAsset::register($this);

?>
<div class="clearfix">
    <div class="main">

        <!-- вкладки навигация и заголовок-->
        <div class="no-left-pd tabs-nav-h1 tabs-nav-wrap text-black pd-top-0 mr-bottom-60 clearfix">
            <h1 class="page-title section-head text-black pull-left"><?= Html::encode($this->title) ?></h1>
            <ul class="nav nav-tabs pull-right" role="tablist">
                <li role="presentation" class="media-nav-tab custom-tab-item active">
                    <a href="#tab_media_all" class="all" role="tab" data-type=""
                       data-toggle="tab" aria-controls="home" aria-expanded="true">Все</a>
                </li>
                <?php foreach ($types as $type): ?>
                    <li role="presentation" class="media-nav-tab custom-tab-item">
                        <a href="#tab_media_<?= $type; ?>" data-type="<?= $type; ?>" role="tab"
                           data-toggle="tab" aria-controls="home"
                           aria-expanded="true"><?= \app\modules\media\models\AbstractMediaModel::getTitleByType($type); ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                         aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>
        <!-- вкладки -->
        <div class="tab-content pd-top-0">
            <!-- вкладка 1 -->
            <div class="tab-pane tab-pane-media all active" data-url="<?= Url::to(['/media']); ?>"
                 role="tabpanel"
                 data-type=""
                 id="tab_media_all" aria-labelledby="home-tab">
                <?= $this->render('_tab', ['dataProvider' => $dataProvider, 'type' => 'all']) ?>
            </div>
            <?php foreach ($types as $type): ?>
                <div class="tab-pane tab-pane-media" data-url="<?= Url::to(['/media']); ?>"
                     role="tabpanel"
                     data-type="<?= $type; ?>"
                     id="tab_media_<?= $type; ?>" aria-labelledby="home-tab">
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <aside class="main-aside">
        <?= $this->render('//parts/right-side-menu') ?>
        <?= $this->render('_search', ['searchModel' => $searchModel]) ?>
    </aside>
</div>