<?php
/**
 * Created by PhpStorm.
 * User: eugene-kei
 * Date: 13.07.17
 * Time: 16:55
 */

use app\components\helpers\StringHelper;
use yii\helpers\Html;

/**
 * @var $this yii\web\View
 * @var $model \app\modules\page\models\Page
 */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'О Министерстве', 'url' => '/ministry/about'];
$this->params['breadcrumbs'][] = ['label' => 'Структура Минтруда России', 'url' => ['/ministry/about/structure']];

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->text), 255),
], 'og:description');

?>

<div class="page-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="page-content"><?= $model->text ?></div>
</div>
