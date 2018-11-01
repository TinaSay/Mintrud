<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.07.17
 * Time: 11:28
 */

/* @var $this yii\web\View */
/* @var $model \app\modules\cabinet\form\VerifyCodeForm */
/* @var $form ActiveForm */

use app\assets\jQueryRunnerAsset;
use app\modules\cabinet\assets\ValidCodeAsset;
use app\modules\cabinet\widgets\bootstrap\Modal;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->registerJs(new JsExpression('var emailWithVerifyCode;'));
$this->registerJs(new JsExpression('jQuery("#registration-with-verify-form").on("afterValidateAttribute", function(event, attribute, messages) { setTimeout(function() { emailWithVerifyCode = attribute.value; if(!messages.length && attribute.name === "email" && localStorage.getItem(emailWithVerifyCode) === null) { jQuery("#registration-with-verify-code-modal").modal("show"); jQuery("#retry-verify-codes-runner").runner("start"); } }, 500); })'));
$this->registerJs(new JsExpression('jQuery("#registration-with-verify-code-form").on("afterValidateAttribute", function(event, attribute, messages) { if(!messages.length && attribute.name === "code") {  localStorage.setItem(emailWithVerifyCode, true); jQuery("#registration-with-verify-code-modal").modal("hide"); } })'));
$this->registerJs(new JsExpression('jQuery("#retry-verify-codes-button").on("ajax.beforeSend", function() { jQuery("#retry-verify-codes-runner").runner("start"); })'));
$this->registerJs(new JsExpression('jQuery("#retry-verify-codes-button").on("ajax.success", function(event, data) { if(data.retry === 0) { jQuery("#registration-with-verify-code-modal").modal("hide"); } })'));
$this->registerJs(new JsExpression('
jQuery("#retry-verify-codes-runner").runner({
    autostart: false,
    countdown: true,
    startAt: 60 * 1000,
    stopAt: 0,
    format: function(value) {
        return "Отправить код повторно можно через " + Math.floor(value / 1000) + " секунд";
    }
}).on("runnerStart", function() {
    jQuery(this).removeClass("hidden");
    jQuery("#retry-verify-codes-button").prop("disabled", true).hide();
}).on("runnerFinish", function() {
    jQuery("#retry-verify-codes-button").prop("disabled", false).show();
    jQuery(this).addClass("hidden");
});
'));

jQueryRunnerAsset::register($this);
ValidCodeAsset::register($this);
?>
<div class="modal-registration-with-verify-code">
    <?php Modal::begin([
        'id' => 'registration-with-verify-code-modal',
        'closeButton' => false,
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
            'show' => false,
        ],
        'header' => '<h4 class="modal-title modal-title--big">Подтвердите адрес электронной почты.</h4>',
    ]); ?>
    <?php $form = ActiveForm::begin([
        'id' => 'registration-with-verify-code-form',
        'action' => ['verify-code'],
    ]); ?>
    <?= $form->field($model, 'code', [
        'enableClientValidation' => false,
        'enableAjaxValidation' => true,
        'template' => '{input}',
        'options' => [
            'class' => 'form-group hidden',
        ],
    ])->textInput() ?>
    <div class="error-message"></div>
    <p class="form-description text-black">
        На указанный почтовый ящик было выслано письмо с кодом подтверждения.
        Для продолжения работы с личный кабинетом, пожалуйста, введите полученный код.
    </p>
    <div data-action="confirm-email" class="confirm-email">
        <div class="confirm-email-code-info">
            <ul class="confirm-email__code">
                <li class="confirm-email__elem">
                    <?= Html::input('text', null, null, [
                        'data-id' => 1,
                        'maxlength' => 1,
                        'class' => 'form-control',
                    ]) ?>
                </li>
                <li class="confirm-email__elem">
                    <?= Html::input('text', null, null, [
                        'data-id' => 2,
                        'maxlength' => 1,
                        'class' => 'form-control',
                    ]) ?>
                </li>
                <li class="confirm-email__elem">
                    <?= Html::input('text', null, null, [
                        'data-id' => 3,
                        'maxlength' => 1,
                        'class' => 'form-control',
                    ]) ?>
                </li>
                <li class="confirm-email__elem">
                    <?= Html::input('text', null, null, [
                        'data-id' => 4,
                        'maxlength' => 1,
                        'class' => 'form-control',
                    ]) ?>
                </li>
            </ul>
            <div class="confirm-email__info text-black">
                <span>
                    Не получили письмо с кодом?<br/>
                    <?= Html::a('Отправить новый код.', ['retry-verify-codes'], [
                        'id' => 'retry-verify-codes-button',
                        'class' => 'send-new-code btn-send-new-code',
                        'data-ajax' => true,
                    ]) ?>
                </span>
                <div class="hidden retry-verify-codes-runner" id="retry-verify-codes-runner"></div>
            </div>
        </div>
        <div class="confirm-email__result"></div>
    </div>
    <?php ActiveForm::end(); ?>
    <?php Modal::end(); ?>
</div>
