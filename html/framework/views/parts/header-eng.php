<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 15.06.2017
 * Time: 19:15
 */

use app\widgets\menu\MenuWidget;
use yii\helpers\Html;

$this->registerMetaTag(['property' => 'sputnik-verification', 'content' => 'HA6pmq04QlFi2bWY']);

\app\assets\FancyBoxAsset::register($this);
?>
<!-- HEADER -->

<!-- показать когда будет Госбар этот спан (загораживает переключчалку с языками на мобильной версии)-->
<span class="hide-lang"></span>


<div class="header-wrap">
    <?= $this->render('//parts/top-header-eng'); ?>
    <nav class="navbar navbar-default top-nav">
        <div class="search-form-header-container">
            <div class="search-form-header">
                <div class="nav-container">
                    <div class="wrap-search-form">
                        <!-- <a href="/" class="navbar-logo" style="display: none;">
                            <span class="logo" style="background-image: url(/static/default/img/icon/logo.svg);"></span>
                        </a> -->
                        <?= Html::beginForm(['/search'], 'get', ['class' => 'header-search-form']) ?>
                        <?= Html::textInput('term', '', ['class' => 'form-control', 'placeholder' => 'Enter a search term']) ?>
                        <?= Html::submitButton('Найти', ['class' => 'btn btn-primary btn-lg search-btn']) ?>
                        <?= Html::endForm() ?>
                        <span class="btn-search-form-hide"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-container">
            <div id="header-menu" class="navbar-header">
                <a href="/eng" class="navbar-logo" style="display: inline-flex;">
                    <span class="logo" style="background-image: url(/static/default/img/icon/logo.svg);"></span>
                    <span>Ministry of Labour<br/>and Social Protection</span>
                </a>
                <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar-main" aria-expanded="false">                      
                    <i class="btn-gamb">
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                    </i>                 
                </button>  
            </div>
            <div class="seach-ell" style="display: none;">
                <a class="btn-search-form-show" title="Поиск" href="#"><img class="search"
                                                                            src="/static/default/img/icon/search.svg"></a>
            </div>
            <?= MenuWidget::widget(['view' => 'default-eng']); ?>
        </div>
    </nav>
</div>
<!-- HEADER END -->
