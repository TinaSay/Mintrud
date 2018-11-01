<?php

use app\modules\council\models\CouncilMeeting;
use app\modules\magic\models\Magic;
use app\modules\magic\widgets\MagicManageWidget;
use app\modules\magic\widgets\MagicUploadWidget;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\council\models\CouncilDiscussion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-header">
    <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
</div>

<div class="card-content">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'meeting_id')->dropDownList(CouncilMeeting::asDropDown(), ['prompt' => 'Выберите заседание']); ?>

    <?= $form->field($model, 'announce')->textarea(['rows' => 3, 'maxlength' => true]) ?>

    <?= $form->field($model, 'text')->widget(
        Yii::createObject([
            'class' => \krok\editor\interfaces\EditorInterface::class,
            'model' => $model,
            'attribute' => 'text',
        ])
    ) ?>

    <?= $form->field($model, 'vote')->dropDownList($model::getVoteList()); ?>

    <?= $form->field($model, 'date_begin')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on'],
        ]
    ) ?>

    <?= $form->field($model, 'date_end')->widget(
        DatePicker::className(),
        [
            'dateFormat' => 'php:Y-m-d',
            'options' => ['class' => 'form-control', 'autocomplete' => 'on'],
        ]
    ) ?>

    <?= $form->field($model, 'hidden')->dropDownList($model::getHiddenList()); ?>

    <?php if ($model->getIsNewRecord()) : ?>
        <h1>Файлы для загрузки</h1>

        <?= MagicUploadWidget::widget(
            [
                'model' => $model,
                'attribute' => 'files',
                'options' => [
                    'multiple' => true,
                ],
            ]
        ); ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('system', 'Create') : Yii::t('system', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->getIsNewRecord()) : ?>

        <h1>Файлы для загрузки</h1>

        <?= MagicManageWidget::widget(
            [
                'model' => new Magic(['scenario' => 'many']),
                'attribute' => Magic::ATTRIBUTE,
                'attributes' => [
                    'module' => $model::className(),
                    'group_id' => 0,
                    'record_id' => $model->id,
                ],
            ]
        ); ?>

    <?php endif; ?>
</div>
