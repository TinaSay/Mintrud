<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/** @var $this yii\web\View */
/** @var $searchModel \app\modules\media\models\search\AudioSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Аудиоматериалы';
$this->params['breadcrumbs'][] = ['label' => 'Пресс-центр', 'url' => '/news/news/list'];

?>
<div class="clearfix">
    <div class="main">

        <h1 class="page-title text-black"><?= Html::encode($this->title) ?></h1>

        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'options' => ['class' => 'list-group list-group-services'],
            'itemView' => '@app/modules/media/views/frontend/media/_item',
            'layout' => '{items}',
        ]) ?>

        <div class="wrap-pagination">
            <?= $this->render('//parts/pagination', ['pagination' => $dataProvider->pagination]) ?>
        </div>
    </div>

    <aside class="main-aside">
        <?= $this->render('//parts/right-side-menu') ?>
    </aside>
</div>