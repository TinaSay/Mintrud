<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $model app\modules\page\models\Structure */

if ($model->isNewRecord) {
    $this->title = 'Создать страницу';
} else {
    $this->title = 'Обновить страницу';
}
$this->params['breadcrumbs'][] = ['label' => 'Страница структуры министерства', 'url' => ['view']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'text')->widget(\app\widgets\editor\EditorWidget::className(), [
            'clientOptions' => [
                'clientOptions' => [
                    'lang' => 'ru'
                ]
            ]
        ]) ?>

        <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ?
                \Yii::t('system','Create') : \Yii::t('system','Update'),
                ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
