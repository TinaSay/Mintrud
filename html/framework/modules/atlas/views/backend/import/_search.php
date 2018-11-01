<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 20.07.17
 * Time: 17:54
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $rateList array */
/* @var $yearList array */
/* @var $model \app\modules\atlas\models\search\AtlasStatSearch */
?>
<div class="card-header">
    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]) ?>

    <div class="panel panel-default">
        <div class="panel-body">

            <?= $form->field($model, 'directory_rate_id')->dropDownList($rateList, [
                'prompt' => 'Выберите',
            ]); ?>

            <?= $form->field($model, 'year')->dropDownList($yearList, [
                'prompt' => 'Выберите',
            ]); ?>

        </div>
        <div class="panel-footer">
            <?= Html::submitButton('Применить', ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>
