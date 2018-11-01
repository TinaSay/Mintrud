<?php

use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $type \app\modules\auth\types\LoginType */
/* @var $form ActiveForm */

$this->title = 'Авторизация';
?>
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-10 col-md-offset-3 col-sm-offset-1">
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                ],
            ]); ?>
            <div class="card card-login swal2-show" data-background="color" data-color="blue">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card-login__title">
                                <a href="<?= Url::to(['/']) ?>" class="logo-big">
                                    <?= Html::img('@web/img/logo-big.svg', ['alt' => 'Лого']) ?>
                                </a>
                                <span class="name">Министерство труда и социальной защиты Российской Федерации</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-login__body row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?= $form->field($type, 'login')->textInput(
                                    ['class' => 'form-control',
                                    'autofocus' => 'autofocus']
                                ) ?>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <?= $form->field($type, 'password')->passwordInput(
                                    ['class' => 'form-control']
                                ) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($type->isCaptcha): ?>
                    <div class="container-fluid gray-bg">
                        <div class="card-login__vertify row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <?= $form->field($type, 'verifyCode')->widget(
                                        Captcha::className(), [
                                        'captchaAction' => '/auth/default/captcha',
                                        'options' => [
                                            'class' => 'form-control'
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="container-fluid">
                    <div class="card-login__footer row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <?= Html::submitButton('Авторизация', ['class' => 'btn btn-fill btn-wd']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
