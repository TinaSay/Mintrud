<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2017
 * Time: 14:40
 */
use yii\helpers\Url;
use yii\helpers\Html;

/** @var $this \yii\web\View */

?>

<nav class="navbar navbar-default top-nav">
    <div class="search-form-header-container">
        <div class="search-form-header">
            <div class="nav-container">
                <div class="wrap-search-form">
                    <!-- <a href="/" class="navbar-logo" style="display: none;">                      
                        <span class="logo" style="background-image: url(/static/default/img/icon/logo.svg);"></span>
                    </a> -->
                    <?= Html::beginForm(['/search'], 'get', ['class' => 'header-search-form']) ?>
                    <?= Html::textInput('term', '', ['class' => 'form-control', 'placeholder' => 'Введите поисковый запрос, например, открытые данные минтруда']) ?>
                    <?= Html::submitButton('Найти', ['class' => 'btn btn-primary btn-lg search-btn']) ?>
                    <?= Html::endForm() ?>
                    <span class="btn-search-form-hide"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="nav-container">
        <div id="header-menu" class="navbar-header">
            <a href="<?= Url::home() ?>" class="navbar-logo">
                <span class="logo" style="background-image: url(/static/default/img/icon/logo.svg);"></span>
                <span>Минтруд<br/>России</span>
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
        <div class="seach-ell">
            <a class="btn-search-form-show" title="Поиск" href="#"><img class="search" src="/static/default/img/icon/search.svg"></a>
        </div>
        <div class="navbar-collapse collapse pull-right" id="navbar-main">
            <ul class="nav navbar-nav">
                <li><a title="О Министерстве" href="/ministry/about">О Министерстве</a></li>
                <li><a title="Деятельность" href="/ministry/programms">Деятельность</a></li>
                <li><a title="Пресс-центр" href="/news/news/list">Пресс-центр</a></li>
                <li><a title="Документы" href="/docs/">Документы</a></li>
                <li><a title="Госуслуги" href="/ministry/services/">Госуслуги</a></li>
                <li><a title="Открытое министерство" href="/ministry/opengov">Открытое министерство</a></li>
                <li><a title="Контакты" href="/ministry/contacts">Контакты</a></li>
            </ul>
        </div>
    </div>
</nav>
