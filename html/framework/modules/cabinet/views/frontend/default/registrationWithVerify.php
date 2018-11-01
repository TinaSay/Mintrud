<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 28.06.17
 * Time: 18:12
 */

use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\cabinet\form\RegistrationWithVerifyForm */
/* @var $verifyCodeForm \app\modules\cabinet\form\VerifyCodeForm */
/* @var $form ActiveForm */

$this->title = Html::encode('Регистрация');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title ?></h1>
                <div class="pd-bottom-80 pd-top-30">
                    <div class="bg-gray bg-box">
                        <div class="container-fluid">
                            <div class="row">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'registration-with-verify-form',
                                    'fieldConfig' => [
                                        'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    ],
                                ]); ?>
                                <div class="col-lg-10">
                                    <div class="error-message">
                                        <?= AlertWidget::widget(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-12">
                                    <?= $form->field($model, 'email', [
                                        'enableClientValidation' => false,
                                        'enableAjaxValidation' => true,
                                        'validateOnChange' => false,
                                        'options' => [
                                            'class' => 'form-group form-group--placeholder-fix',
                                        ],
                                        'labelOptions' => [
                                            'class' => 'placeholder',
                                        ],
                                    ])->textInput() ?>
                                </div>
                                <div class="col-lg-8 col-md-12">
                                    <?= $form->field($model, 'password', [
                                        'enableClientValidation' => false,
                                        'enableAjaxValidation' => true,
                                        'validateOnChange' => false,
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
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="form-control__description">
                                        Пароль должен содержать буквы и цифры. Минимальная длина пароля - 8 символов.
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-12">
                                    <div class="two-btn">
                                        <?= Html::submitButton('Регистрация',
                                            ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>
                                        <a href="<?= Url::to(['login-with-email']) ?>" class="link-gray two-btn__elem">
                                            Войти в кабинет
                                        </a>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->render('_nav', ['active' => 'registration']) ?>
        </div>
    </div>
</section>

<?= $this->render('_registrationWithVerifyCodeModal', ['model' => $verifyCodeForm]) ?>
