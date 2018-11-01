<?php

use yii\helpers\Html;

/** @var $this yii\web\View */
/** @var $tree \app\modules\subdivision\models\Subdivision[] */

$this->title = 'Подразделения';
$this->params['breadcrumbs'][] = $this->title;

// sortable
$url = \yii\helpers\Url::to(['update-all']);

?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>

    <div class="card-content">

        <?= \app\widgets\tree\TreeWidget::widget(
            [
                'attributeContent' => function ($item) {
                    return $item['title'];
                },
                'items' => $tree,
                'clientEvents' => [
                    'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . $url . '\'}) }',
                ],
            ]
        ) ?>

    </div>
</div>
