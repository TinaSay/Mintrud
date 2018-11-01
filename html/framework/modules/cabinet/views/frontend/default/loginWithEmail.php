<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 27.06.17
 * Time: 8:46
 */

use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\cabinet\form\LoginWithEmailForm */
/* @var $form ActiveForm */

$this->title = Html::encode('Авторизация');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title ?></h1>
                <?php $alerts = AlertWidget::widget(); ?>
                <?php if ($alerts): ?>
                    <div class="row">
                        <div class="col-md-12">
                            <?= $alerts; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="pd-bottom-80 pd-top-30">
                    <div class="bg-gray bg-box  auth-box">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="tabs-nav-wrap tabs-nav-wrap--auth text-black mr-bottom-0">
                                        <div class="no-left-pd">
                                            <ul class="nav nav-tabs nav-tabs--left">
                                                <li class="custom-tab-item active">
                                                    <a data-toggle="tab" href="#panel1">Учетная запись</a>
                                                </li>
                                                <li class="custom-tab-item">
                                                    <a data-toggle="tab" href="#panel2">Войти через Портал
                                                        Госуслуг</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                                <div class="tab-content clearfix pd-bottom-0">
                                    <div id="panel1" class="tab-pane fade in active">
                                        <?php $form = ActiveForm::begin([
                                            'fieldConfig' => [
                                                'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                            ],
                                        ]); ?>
                                        <div class="col-xs-12">
                                            <div class="error-message">
                                                <?= AlertWidget::widget(); ?>
                                            </div>
                                        </div>
                                        <div class="col-xs-12">
                                            <div class="form-block-two">

                                                <?= $form->field($model, 'email', [
                                                    'enableClientValidation' => false,
                                                    'enableAjaxValidation' => true,
                                                    'validateOnChange' => false,
                                                    'validateOnBlur' => false,
                                                    'options' => [
                                                        'class' => 'form-group big-field',
                                                    ],
                                                    'labelOptions' => [
                                                        'class' => 'placeholder',
                                                    ],
                                                ])->textInput() ?>

                                                <?= $form->field($model, 'password', [
                                                    'enableClientValidation' => false,
                                                    'enableAjaxValidation' => true,
                                                    'validateOnChange' => false,
                                                    'validateOnBlur' => false,
                                                    'options' => [
                                                        'class' => 'form-group big-field',
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

                                            <div class="two-btn">
                                                <?= Html::submitButton('Войти',
                                                    ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>
                                                <a href="<?= Url::to(['reset-with-verify']) ?>"
                                                   class="link-gray two-btn__elem">
                                                    Забыли пароль?
                                                </a>
                                            </div>

                                        </div>
                                        <?php ActiveForm::end(); ?>
                                        <div class="col-xs-12 pd-top-40 text-gray">
                                            У вас нет личного кабинета?
                                            <a href="<?= Url::to(['registration-with-verify']) ?>"
                                               class="link-underline">
                                                Зарегистрируйтесь прямо сейчас
                                            </a>
                                        </div>
                                    </div>
                                    <div id="panel2" class="tab-pane fade">
                                        <div class="col-xs-12">
                                            <div class="form-sub-title-type-2 text-dark">Войти на сайт через ЕСИА
                                            </div>
                                            <p>Вы можете авторизоваться через Интернет-портал государственных
                                                услуг</p>
                                            <a href="<?= Url::to([
                                                '/cabinet/default/oauth',
                                                'authclient' => 'esia',
                                            ]) ?>"
                                               class="btn btn-primary btn-lg two-btn__elem link-auth-gos mr-top-40"><span><i></i>Войти через Портал Госуслуг</span></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php /*
                            <!-- в новом макете нет входа через соцсети, скрываю пока -->
                            <div class="row" style="display: none;">
                                <div class="col-lg-5 text-gray bold">Или авторизуйтесь через социальные сети</div>
                                <div class="col-lg-7">
                                    <?= $this->render('_social', ['baseAuthUrl' => ['/cabinet/default/oauth']]) ?>
                                </div>
                            </div>*/
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->render('_nav', ['active' => 'login']) ?>
        </div>
    </div>
</section>
