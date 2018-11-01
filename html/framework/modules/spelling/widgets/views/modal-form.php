<?php
/**
 * @var $model \app\modules\spelling\models\Spelling
 */
use app\modules\spelling\widgets\SpellingWidgetAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

SpellingWidgetAsset::register($this);
?>

<div id="spelling-modal" class="modal fade" style="display: none" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Сообщить об ошибке</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'action' => Url::to(['/spelling/default/send']),
                    'options' => [
                        'class' => 'form form-comment',
                        'id' => 'spelling-form',
                    ],
                    'enableClientScript' => false,
                ]);?>

                <div class="error-message"></div>

                <?=$form->field($model, 'url')->hiddenInput()->label(false);?>

                <p id="spelling-message" class="hidden alert"></p>

                <?=$form->field($model, 'selectedText')->hiddenInput()->label(false);?>
                <?=Html::activeLabel($model, 'selectedText');?>
                <p id="spelling-selected-text" class="alert alert-warning"></p>

                <?=Html::activeLabel($model, 'comment');?>
                <?=$form->field($model, 'comment' , [
                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                    'options' => [
                        'class' => 'form-group form-group--placeholder-fix',
                    ],
                    'labelOptions' => [
                        'class' => 'placeholder',
                    ],
                    ])
                ->textarea(['rows' => 3]);?>
                <div>
                    <button id="spelling-submit-button" type="submit" class="btn btn-primary btn-lg two-btn__elem">Отправить</button>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>

<div id="modalOk" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Спасибо!</h4>
                <div class="text-black pd-top-10">Ваше сообщение отправлено.</div>
            </div>
        </div>
    </div>
</div>
