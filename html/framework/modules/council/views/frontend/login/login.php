<?php

use app\modules\council\assets\CouncilSettingsAsset;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\council\forms\LoginForm */
/* @var $form ActiveForm */

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = Html::encode('Общественные обсуждения');

CouncilSettingsAsset::register($this);
?>

<div class="row">
    <div class="main col-md-12">
        <h1 class="page-title text-black">Личный кабинет участника обсуждения</h1>
        <div class="pd-bottom-80">
            <div class="bg-gray bg-box">
                <div class="container-fluid">
                    <div class="row">

                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                            ],
                            'enableClientValidation' => false,
                            'options' => [
                                'id' => 'formLkLogin',
                            ],
                        ]); ?>
                        <div class="col-lg-10">
                            <div class="error-message"></div>
                        </div>
                        <div class="col-lg-5 col-md-8">
                            <?= $form->field($model, 'login', [
                                'options' => [
                                    'class' => 'form-group form-group--placeholder-fix',
                                ],
                                'labelOptions' => [
                                    'class' => 'placeholder',
                                ],
                            ])->textInput() ?>
                            <?= $form->field($model, 'password', [
                                'options' => [
                                    'class' => 'form-group form-group--placeholder-fix',
                                ],
                                'labelOptions' => [
                                    'class' => 'placeholder',
                                ],
                                'template' => '{label}' . PHP_EOL . '{passwordShow}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                'parts' => [
                                    '{passwordShow}' => '<i class="password-show"></i>',
                                ],
                            ])->passwordInput() ?>
                            <?php /*<div class="form-group">
                                <?= $form->field($model, 'verifyCode')->widget(
                                    Captcha::className(), [
                                    'captchaAction' => '/lk/login/captcha',
                                    'options' => [
                                        'class' => 'form-control',
                                    ],
                                ]) ?>
                            </div> */?>
                            <div class="two-btn">
                                <?= Html::submitButton('Войти', ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>

                                <a href="#" class="link-gray two-btn__elem">Забыли пароль</a>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>         

