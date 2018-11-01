<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 21.11.16
 * Time: 12:27
 */

/* @var $this yii\web\View */
/* @var $model app\modules\comment\models\Comment */
/* @var $parentId int */
/* @var $authorized bool */
/* @var $showForm bool */

use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(
    [
        'action' => ['/comment/default/create'],
        'options' => [
            'class' => 'form-comment form-discussion-comment' . ($showForm ? '' : ' hidden'),
        ],
        'enableClientScript' => false,
    ]) ?>

<?php if ($parentId > 0): ?>
    <?= $form->field($model, 'parent_id', ['template' => '{input}'])->hiddenInput([
        'value' => $parentId,
        'class' => 'parent_id',
    ]) ?>
<?php endif; ?>

<?= $form->field($model, 'model', ['template' => '{input}'])->hiddenInput() ?>

<?= $form->field($model, 'record_id', ['template' => '{input}'])->hiddenInput() ?>

<?= \app\widgets\alert\AlertWidget::widget(); ?>
<div class="error-message"></div>
<?= $form->field($model, 'text', [
    'options' => [
        'class' => 'form-group form-group--placeholder-fix',
    ],
    'labelOptions' => [
        'class' => 'placeholder',
    ],
])->textarea(
    [
        'required' => true,
    ]
)->label('Добавьте свой комментарий....') ?>
<div>
    <button type="submit" class="btn btn-primary btn-md">Опубликовать</button>
</div>
<?php ActiveForm::end(); ?>

<div class="comment-info-form text-black<?= ($showForm ? ' hidden' : '') ?>">
    <p>Для публикации комментария в данном обсуждении, пожалуйста,
        проголосуйте в блоке</p>
    <a class="link-animate-scroll link-bold" href="#addVote">
        «Ваше мнение»</a>
</div>
