<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 16:22
 */

use app\modules\document\assets\DocumentSearchAsset;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models \app\modules\document\models\Document[] */
/** @var $context \app\modules\document\controllers\frontend\DocumentController */
/** @var $directory \app\modules\directory\models\Directory */
/** @var $searchModel \app\modules\document\models\search\DocumentSearch */
/** @var $pagination \yii\data\Pagination */
/** @var $tabs \app\modules\document\models\WidgetOnMain[] */

DocumentSearchAsset::register($this);

$context = $this->context;

$this->title = $directory->title;

$breadcrumbs = $context->getBreadcrumbs($directory->id);
array_pop($breadcrumbs);
$this->params['breadcrumbs'] = array_merge($breadcrumbs, ['label' => $this->title]);


?>

<div class="clearfix">
    <div class="main">

        <div class="no-left-pd tabs-nav-h1 tabs-nav-wrap text-black pd-top-0 mr-bottom-60 clearfix">
            <h1 class="page-title section-head text-black pull-left"><?= $this->title ?></h1>
            <ul class="nav nav-tabs pull-right" role="tablist">
                <li role="presentation" class="document-nav-tab custom-tab-item active">
                    <a href="#tab_doc_all" class="all" role="tab" data-type="0" data-toggle="tab" aria-controls="home"
                       aria-expanded="true">Все</a>
                </li>
                <?php foreach ($tabs as $tab): ?>
                    <li role="presentation" class="document-nav-tab custom-tab-item">
                        <a href="#tab_doc_<?= $tab->id ?>" data-type="<?= $tab->type_document_id; ?>" role="tab"
                           data-toggle="tab" aria-controls="home"
                           aria-expanded="true">
                            <?= $tab->title ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>
        <div class="tab-content pd-top-0">
            <div class="tab-pane tab-pane-docs all active" data-loaded="1" data-url="<?= $directory->asUrl() ?>"
                 role="tabpanel"
                 data-type="0"
                 id="tab_doc_all" aria-labelledby="home-tab">
                <?= $this->render('_items', [
                    'models' => $models,
                    'pagination' => $pagination,
                ]); ?>
            </div>
            <?php foreach ($tabs as $tab): ?>
                <div class="tab-pane tab-pane-docs"
                     data-type="<?= $tab->type_document_id; ?>"
                     data-url="<?= Url::to(['/docs']); ?>"
                     role="tabpanel"
                     id="tab_doc_<?= $tab->id; ?>">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <aside class="main-aside">
        <div class="border-block">
            <?= $this->render('_search', ['searchModel' => $searchModel]) ?>
        </div>
        <div class="border-block">
            <?= $this->render('_rss') ?>
        </div>
    </aside>
</div>
