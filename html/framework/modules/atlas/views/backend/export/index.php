<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $yearList array */
/* @var $searchModel \app\modules\atlas\models\search\AtlasStatSearch */

$this->title = 'Экспорт статистики';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
$(".filter-year").on("change", function(){
    var val = $(this).find("option:selected").val();
    if(val > ""){
        $(".filter-export.btn").removeClass("hidden");
    } else {
        $(".filter-export.btn").addClass("hidden");
    }
});
');
?>

<div class="card">
    <div class="group-index">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-header">
            <?php $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]) ?>

            <div class="panel panel-default">
                <div class="panel-body">

                    <?= $form->field($searchModel, 'year')->dropDownList($yearList, [
                        'prompt' => 'Выберите',
                        'class' => 'form-control filter-year',
                    ]); ?>

                </div>
                <div class="panel-footer">
                    <?= Html::submitButton('Экспортировать', [
                        'name' => 'export',
                        'value' => 1,
                        'class' => 'hidden filter-export btn btn-success',
                    ]); ?>
                </div>
            </div>

            <?php ActiveForm::end() ?>
        </div>

        <div class="card-content">
            <p>Для начала экспорта выберите Год</p>
        </div>
    </div>
</div>