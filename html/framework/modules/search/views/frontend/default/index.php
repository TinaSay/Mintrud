<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 08.08.17
 * Time: 17:40
 */

/* @var $this yii\web\View */
/* @var $term string */
/* @var $found array */
/* @var $pagination \yii\data\Pagination */
/* @var $module string */
/* @var $modules array */

/* @var $sort string */

use yii\helpers\Html;

$this->title = 'Результаты поиска';
$this->params['breadcrumbs'][] = Html::encode('Результаты поиска');

?>
<div class="row">
    <div class="main col-md-12">
        <h1 class="page-title text-black"><?= Html::encode($this->title) ?></h1>
        <div class="pd-bottom-80 pd-top-30">
            <div class="page-view">
                <div class="page-content">
                    <div class="wrap-search-form">
                        <div class="search__nav-top tabs-nav-wrap text-black pd-top-30 mr-bottom-40">
                            <div class="no-left-pd">
                                <ul class="nav nav-tabs">
                                    <li class="custom-tab-item <?php if ($sort == 'createdAt') : ?>active<?php endif; ?>">
                                        <?= Html::a('По дате',
                                            ['/search', 'term' => $term, 'module' => $module, 'sort' => 'createdAt']) ?>
                                    </li>
                                    <li class="custom-tab-item <?php if (is_null($sort)) : ?>active<?php endif; ?>">
                                        <?= Html::a('По релевантности',
                                            ['/search', 'term' => $term, 'module' => $module]) ?>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <?= Html::beginForm(['/search'], 'get') ?>
                        <?= Html::textInput('term', $term, ['class' => 'form-control']) ?>
                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary btn-lg search-btn']) ?>
                        <?= Html::endForm() ?>
                        <div class="search__nav-bottom tabs-nav-wrap text-black pd-top-50 mr-bottom-15">
                            <div id="navbar-search" class="no-left-pd">
                                <ul class="nav nav-tabs">
                                    <li class="custom-tab-item <?php if (is_null($module)) : ?>active<?php endif; ?>">
                                        <?= Html::a('Все', ['/search', 'term' => $term, 'sort' => $sort]) ?>
                                    </li>
                                    <?php foreach ($modules as ['title' => $title, 'filter' => $filter]) : ?>
                                        <li class="custom-tab-item <?php if ($filter == $module) : ?>active<?php endif; ?>">
                                            <?= Html::a($title, [
                                                '/search',
                                                'term' => $term,
                                                'module' => $filter,
                                                'sort' => $sort,
                                            ]) ?>
                                        </li>
                                    <?php endforeach; ?>
                                    <li class="tabs-container dropdown">
                                        <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown"
                                             aria-haspopup="true" aria-expanded="false"></div>
                                        <div class="tabs-container__content dropdown-menu"></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pd-top-60">
                        <?php if (is_array($found) && !empty($found)) : ?>
                            <ul class='ul-post-list'>
                                <?php foreach ($found as $row) : ?>
                                    <li>
                                        <?php if ($row['created_at']): ?>
                                            <p class="page-date text-light">
                                                <?= Yii::$app->formatter->asDate($row['created_at'], 'dd MMMM yyyy'); ?>
                                                <?php if ($row['updated_at'] > $row['created_at']): ?> -
                                                    <?= Yii::$app->formatter->asDate($row['updated_at'],
                                                        'dd MMMM yyyy'); ?>
                                                <?php endif; ?>
                                            </p>
                                        <?php endif; ?>
                                        <?= Html::a($row['title'], $row['url'], ['target' => '_blank']) ?>
                                        <p><?= $row['snippet'] ?></p>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
                        <?php else : ?>
                            <p><?= Yii::t('system', 'Nothing found') ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
