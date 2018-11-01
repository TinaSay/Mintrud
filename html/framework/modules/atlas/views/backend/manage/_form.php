<?php

use app\modules\atlas\models\AtlasDirectoryAllowance;
use app\modules\atlas\models\AtlasDirectorySubjectRf;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\atlas\models\AtlasAllowance */
?>

<?= $form->field($model,
    'directory_subject_id')->dropDownList(AtlasDirectorySubjectRf::asDropDown()) ?>

<?= $form->field($model,
    'directory_allowance_id')->dropDownList(AtlasDirectoryAllowance::asDropDown()) ?>

<?= $form->field($model, 'federal')->widget(
    Yii::createObject([
        'class' => \krok\editor\interfaces\EditorInterface::class,
        'model' => $model,
        'attribute' => 'federal',
    ])
) ?>

<?= $form->field($model, 'regional')->widget(
    Yii::createObject([
        'class' => \krok\editor\interfaces\EditorInterface::class,
        'model' => $model,
        'attribute' => 'regional',
    ])
) ?>
