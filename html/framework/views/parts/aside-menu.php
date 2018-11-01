<?php
/**
 * @var $this yii\web\View
 * @var $model \app\modules\page\models\Structure;
 * @var $subdivisions \app\modules\subdivision\models\Subdivision[]
 */

use yii\helpers\Url;

?>
<ul class="list-nav no-mr-top list-nav--light">
    <li <?= ('/ministry/about/structure' == \Yii::$app->controller->route) ? 'class="active"' : '' ?>>
        <a class="text-dark" href="<?= Url::to(['/ministry/about/structure']) ?>">Структура Минтруда России</a>
    </li>
    <li><a class="text-dark" href="/ministry/about/issues">Вопросы Минтруда России</a></li>
    <li><a class="text-dark" href="/ministry/about/reports">Доклады о результатах и основных направлениях деятельности Минтруда России</a>
    </li>
    <li><a class="text-dark" href="/ministry/about/5">Судебный и административный порядок оспаривания нормативных правовых актов и иных
            решений, действий (бездействия) Минтруда России</a></li>
    <li><a class="text-dark" href="/ministry/about/7">Перечень судебных постановлений по делам о признании недействующими нормативных
            правовых актов Минтруда России</a></li>
    <li><a class="text-dark" href="/ministry/about/succession">О правопреемственности</a></li>
    <li><a class="text-dark" href="/ministry/about/6">Информация о проверках, проводимых Минтрудом России</a></li>
    <li><a class="text-dark" href="/ministry/about/8">Информация об отсутствии территориальных органов и зарубежных
            представительств</a></li>
</ul>
<div class="border-block doc-aside" style="display: none;">
    <a href="#" class="btn btn-block btn-primary">Сохранить в Word</a>
    <div class="doc-aside-info text-black">
        <span>Формат: DOC</span>
        <span>Размер: 19 Kb</span>
    </div>
</div>
