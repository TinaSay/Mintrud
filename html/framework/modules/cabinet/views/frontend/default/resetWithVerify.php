<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.07.17
 * Time: 15:26
 */

use app\widgets\alert\AlertWidget;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\cabinet\form\ResetWithVerifyForm */
/* @var $verifyCodeForm \app\modules\cabinet\form\VerifyCodeForm */
/* @var $form ActiveForm */

$this->title = Html::encode('Восстановление доступа');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>
<div class="default-reset">

    <section class="pd-top-0 pd-bottom-30">
        <div class="container">
            <div class="clearfix">
                <div class="main">
                    <h1 class="page-title text-black"><?= $this->title ?></h1>
                    <div class="pd-bottom-80 pd-top-30">
                        <div class="bg-gray bg-box">
                            <div class="container-fluid">
                                <div class="row">
                                    <?= AlertWidget::widget(); ?>
                                    <?php $form = ActiveForm::begin([
                                        'action' => ['reset-with-verify'],
                                        'id' => 'reset-with-verify-form',
                                        'enableClientValidation' => true,
                                        'enableAjaxValidation' => false,
                                        'fieldConfig' => [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                        ],
                                    ]); ?>
                                    <?= Html::input('hidden', 'scenario', $model->getScenario(), [
                                        'class' => 'form-scenario',
                                    ]); ?>
                                    <div class="col-lg-10">
                                        <div class="error-message">
                                            <?= AlertWidget::widget(); ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <?= $form->field($model, 'email', [
                                            'validateOnChange' => false,
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                        ])->textInput() ?>

                                        <div class="form-group hidden">
                                            <?= $form->field($model, 'password', [
                                                'validateOnChange' => false,
                                            ])->passwordInput(
                                                ['placeholder' => $model->getAttributeLabel('password'), 'value' => '']
                                            ) ?>
                                        </div>
                                        <div class="form-group big-field">
                                            <?= $form->field($model, 'captcha')->widget(
                                                Captcha::className(), [
                                                'captchaAction' => '/cabinet/default/reset-captcha',
                                                'options' => [
                                                    'class' => 'form-control',
                                                ],
                                            ]) ?>
                                        </div>
                                    </div>
                                    <div class="col-lg-8 col-md-12">
                                        <div class="two-btn">
                                            <?= Html::submitButton('Отправить',
                                                ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                                    <div class="success-restore hidden col-md-12">
                                        <div class="alert alert-success">
                                            Вы успешно восстановили пароль
                                        </div>
                                        <a href="<?= Url::to(['/cabinet/view/index']) ?>">
                                            Личный кабинет
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div><!-- default-reset -->

<?= $this->render('_resetWithVerifyCodeModal', ['model' => $verifyCodeForm]) ?>
