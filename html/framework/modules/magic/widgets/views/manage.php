<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.02.15
 * Time: 21:41
 */

/* @var $this yii\web\View */
/* @var $id string */
/* @var $model /app/modules/magic/model/Magic */
/* @var $attribute string */
/* @var $fileInputOptions [] */
/* @var $list [] */

use app\assets\FancyBoxAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\jui\Sortable;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

// sortable
$url = Url::to(['/magic/manage/update']);

FancyBoxAsset::register($this);
?>

<?php Pjax::begin([
    'enablePushState' => false,
    'timeout' => 10000,
    'options' => [
        'id' => 'pjax_' . $id,
    ],
    'clientOptions' => [
        'type' => 'post',
    ],
]) ?>

<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('magic', 'Download file') ?></div>
    <div class="panel-body">
        <?php $form = ActiveForm::begin([
            'action' => ['/magic/manage/upload'],
            'enableClientScript' => false,
            'options' => [
                'id' => 'magic_form_upload_' . $id,
                'data-pjax' => 1,
                'enctype' => 'multipart/form-data',
            ],
        ]); ?>
        <?= $form->field($model, 'module', ['template' => '{input}'])->hiddenInput(); ?>
        <?= $form->field($model, 'group_id', ['template' => '{input}'])->hiddenInput(); ?>
        <?= $form->field($model, 'record_id', ['template' => '{input}'])->hiddenInput(); ?>
        <?= $form->field($model, $attribute)->fileInput($fileInputOptions); ?>
        <?= Html::submitButton(Yii::t('magic', 'Upload'), ['class' => 'btn btn-success']); ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php $form = ActiveForm::begin([
    'action' => ['/magic/manage/update'],
    'enableClientScript' => false,
    'options' => [
        'id' => 'magic_form_update_' . $id,
        'data-pjax' => 1,
    ],
]); ?>
<div class="panel panel-default">
    <div class="panel-heading"><?= Yii::t('magic', 'Uploaded files') ?></div>
    <div class="panel-body">
        <?= Sortable::widget([
            'items' => $list,
            'options' => [
                'id' => 'magic_sortable_' . $id,
                'tag' => 'div',
                'class' => 'row',
                'style' => 'margin-top: 20px;',
            ],
            'itemOptions' => [
                'tag' => 'div',
                'class' => 'col-sm-6 col-md-4',
            ],
            'clientOptions' => [
                'cursor' => 'move',
                'beforeStop' => new JsExpression("
                    function (event, ui) {
                        jQuery(ui.item.parent()).sortable('option', 'disabled', true);

                        jQuery(ui.item.parent()).find('div.caption').each(function (index, el) {
                            jQuery(el).find('input[type=hidden]').val(index + 1);
                        });

                        jQuery.ajax({
                            'type': 'post',
                            'url': '{$url}',
                            'dataType': 'html',
                            'data': jQuery(this).closest('form').serialize()
                        }).done(function () {
                            jQuery(ui.item.parent()).sortable('option', 'disabled', false);
                        });
                    }"
                ),
            ],
        ]); ?>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php Pjax::end() ?>
