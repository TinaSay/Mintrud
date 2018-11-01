<?php

use app\modules\ministry\models\MinistryAssignment;
use app\modules\ministry\models\Ministry;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model MinistryAssignment */
?>
<?= $form->field($model, 'auth_id')->dropDownList(MinistryAssignment::asAuthDropDown([$model->auth_id]), [
    'title' => 'Выберите автора',
    'data-live-search' => 'true',
    'data-actions-box' => 'true',
]); ?>

<?= $form->field($model, 'ministryIds')->dropDownList(Ministry::asDropDown(), [
    'multiple' => true,
    'data-live-search' => 'true',
    'data-actions-box' => 'true',
]); ?>
