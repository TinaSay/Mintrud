<?php

use app\modules\cabinet\assets\BlindConfigureAsset;
use app\modules\cabinet\widgets\menu\Menu;
use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\cabinet\models\Client */
/* @var $changePassword \app\modules\cabinet\form\ChangePasswordForm */
/* @var $lastLoginAt \app\modules\cabinet\models\Log|null */
/* @var $form ActiveForm */

BlindConfigureAsset::register($this);

$this->title = Html::encode('Профиль');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>
<section class="pd-top-0 pd-bottom-40">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <?= $this->render('//parts/breadcrumbs'); ?>
                <div class="pd-top-0">
                    <h1 class="page-title text-black"><?= $this->title ?></h1>
                    <div class="pd-top-10">
                        <?php if (!$model->registeredBySocial()): ?>
                            <div class="bg-gray bg-box pd-top-35 pd-bottom-60">
                                <div class="container-fluid">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="bg-box__title text-black">Изменить пароль</div>
                                        </div>

                                        <?php $form = ActiveForm::begin([
                                            'action' => ['change-password'],
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
                                            <?= $form->field($changePassword, 'password', [
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
                                            <?= $form->field($changePassword, 'newPassword', [
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
                                            <div class="two-btn">
                                                <?= Html::submitButton('Сохранить',
                                                    ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>
                                            </div>
                                        </div>
                                        <?php ActiveForm::end(); ?>

                                    </div>
                                </div>
                            </div>
                            <?php /*
                        <div class="bg-gray bg-box pd-top-35 pd-bottom-60">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="bg-box__title text-black">Изменить данные</div>
                                    </div>

                                    <?php $form = ActiveForm::begin([
                                        'action' => ['change-data'],
                                        'fieldConfig' => [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                        ],
                                    ]); ?>
                                    <div class="col-lg-8 col-md-12">
                                        <?= $form->field($model, 'lastName', [
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
                                        <?= $form->field($model, 'firstName', [
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
                                        <?= $form->field($model, 'middleName', [
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
                                        <div class="two-btn">
                                            <?= Html::submitButton('Сохранить',
                                                ['class' => 'btn btn-primary btn-lg two-btn__elem']) ?>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end(); ?>

                                </div>
                            </div>
                        </div>
                        */ ?>
                        <?php endif; ?>
                        <div class="border-block border-block--bg-box mr-top-20 pd-top-35 pd-bottom-60">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="bg-box__title text-black">Режим работы сайта</div>
                                        <div class="two-btn two-btn--ix2">
                                            <a href="#" class="btn btn-sm btn-grey two-btn__elem default-site-version">
                                                Обычный
                                            </a>
                                            <a href="#" class="btn btn-sm btn-primary two-btn__elem open-modal-blind">
                                                Версия для слабовидящих
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="main-aside pd-top-55">
                <?= Menu::widget() ?>
                <?php if (!is_null($lastLoginAt)) : ?>
                    <div class="border-block">
                        <h4 class="text-uppercase text-prime pd-bottom-15">Ваша активность</h4>
                        <div class="pd-top-5 text-black text-xs">
                            Последний раз вы заходили в личный кабинет
                        </div>
                        <div class="pd-bottom-10 text-black text-bold">
                            <?= Yii::$app->getFormatter()->asDatetime($lastLoginAt->created_at,
                                'dd MMMM yyyy года в HH:mm') ?>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
