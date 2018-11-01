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
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\app\modules\cabinet\assets\ResetWithVerifyFromAsset::register($this);
jQueryRunnerAsset::register($this);
?>
<div class="modal-reset-with-verify-code">
    <?php Modal::begin([
        'id' => 'reset-with-verify-code-modal',
        'size' => Modal::SIZE_LARGE,
        'closeButton' => false,
        'clientOptions' => [
            'backdrop' => 'static',
            'keyboard' => false,
            'show' => false,
        ],
        'header' => '<h2>Подтверждение Email адреса</h2>',
    ]); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        На указанный электронный адрес выслан код подтверждения. Скопируйте его в поле ниже.
                    </h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-with-verify-code-form',
                        'action' => ['verify-code'],
                        'fieldConfig' => [
                            'template' => '{input}' . PHP_EOL . '{error}',
                        ],
                    ]); ?>
                    <fieldset>
                        <div class="form-group">
                            <?= $form->field($model, 'code', [
                                'enableClientValidation' => false,
                                'enableAjaxValidation' => true,
                                'validateOnChange' => false,
                            ])->textInput(
                                ['autofocus' => true, 'placeholder' => $model->getAttributeLabel('code')]
                            ) ?>
                        </div>
                        <div class="form-group hidden">
                            <?= $form->field($model, 'password', [
                                'enableClientValidation' => false,
                                'enableAjaxValidation' => true,
                                'validateOnChange' => false,
                            ])->passwordInput(
                                ['placeholder' => $model->getAttributeLabel('password'), 'value' => '']
                            ) ?>
                        </div>
                        <?= Html::button('Подтвердить', ['class' => 'btn btn-lg btn-success btn-block']) ?>
                        <?= Html::button('Отправить ещё раз', [
                            'id' => 'reset-retry-verify-codes-button',
                            'class' => 'btn btn-lg btn-success btn-block',
                            'href' => Url::to(['retry-verify-codes']),
                            'data-ajax' => true,
                        ]) ?>
                        <span id="reset-retry-verify-codes-runner" class="help-block hidden"></span>
                    </fieldset>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>

        </div>
    </div>
    <?php Modal::end(); ?>
</div>
