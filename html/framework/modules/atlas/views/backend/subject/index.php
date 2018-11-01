<?php

use app\widgets\tree\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $tree array */

$this->title = Yii::t('system', 'Subjects of RF');
$this->params['breadcrumbs'][] = $this->title;

// sortable
$url = Url::to(['update-all']);

?>

<div class="card">
    <div class="group-index">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-header">
            <p>
                <?=
                Html::a(
                    Yii::t(
                        'system',
                        'Create'
                    ),
                    ['create'],
                    ['class' => 'btn btn-success']
                ) ?>
            </p>
        </div>
        <?=
        TreeWidget::widget(
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