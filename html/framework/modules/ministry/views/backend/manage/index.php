<?php

use app\modules\ministry\models\Ministry;
use app\widgets\tree\TreeWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $tree [] */

$this->title = Yii::t('system', 'Content pages');
$this->params['breadcrumbs'][] = $this->title;

// sortable
$url = Url::to(['update-all']);

$this->registerJs('
 $.expr[":"].contains = $.expr.createPseudo(function (arg) {
        return function (elem) {
            return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
        };
    });
    $("form.contains").on("submit", function (e) {
        e.preventDefault();
        var search = $(this).find("input.form-control:first").val();
        var where = $("#" + $(this).data("target"));
        if (where.length <= 0) return false;
        if (search.length) {
            where.find("li").hide();
            where.find("li:contains(" + search + ")").show();
        } else {
            where.find("li").show();
        }
    });
    $("form.contains .form-control").on("keyup", function(){
       var val = $(this).val(),
       $form = $(this).closest("form");
       if(val.length >= 2){
           $form.trigger("submit");
       }else{
          var where = $("#" + $form.data("target"));
          where.find("li").show();
       }
    });
    
    ');

?>
<div class="ministry-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?=
        Html::a(
            'Добавить папку',
            ['create'],
            ['class' => 'btn btn-success']
        ) ?>
    </p>

    <form action="" method="post" class="contains" data-target="ministry_tree_widget">
        <div class="row clearfix">
            <div class="col-md-12 form-group">
                <label class="control-label" for="search_contains">Введите название материала</label>
                <input class="form-control" name="search" id="search_contains" value="">
            </div>
        </div>
    </form>
    <?=
    TreeWidget::widget(
        [
            'options' => [
                'id' => 'ministry_tree_widget',
            ],
            'attributeContent' => function ($item) {
                return Ministry::getIcon($item['type']) . Html::tag('span', $item['title'], ['class' => 'name']);
            },
            'items' => $tree,
            'clientEvents' => [
                'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . $url . '\'}) }',
            ],
            'additionalControls' => [
                'add-item' => function ($item) {
                    if ($item['type'] == Ministry::TYPE_FOLDER) {
                        return Html::a('<i class="add-item ti-plus" title="Создать дочерний элемент"></i>',
                            ['create', 'parent_id' => $item['id']]);
                    }

                    return '';
                },
                'active' => function ($item) {
                    if ($item['hidden'] == Ministry::HIDDEN_YES) {
                        return Html::a('<i class="enable glyphicon glyphicon-ban-circle" title="Показывать"></i>',
                            ['active', 'id' => $item['id']]);
                    } else {
                        return Html::a('<i class="disable glyphicon glyphicon-ok" title="Скрыть"></i>',
                            ['active', 'id' => $item['id']]);
                    }

                },
                'link' => function ($item) {
                    if ($item['hidden'] == Ministry::HIDDEN_NO) {
                        return Html::a('<i class="link glyphicon glyphicon-share-alt" title="Просмотр"></i>',
                            '/' . $item['url'],
                            ['target' => '_blank']);
                    }

                    return '';
                },
            ],
        ]
    ) ?>

</div>
