<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 12:07
 */

use app\modules\council\assets\CouncilSettingsAsset;
use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\council\models\CouncilMember */

$this->title = Html::encode('Изменение пароля');

$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Discussion'), 'url' => ['/lk/discussion/index']];
$this->params['breadcrumbs'][] = Yii::t('system', 'Settings');

CouncilSettingsAsset::register($this);
?>
<!-- page content -->
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title; ?></h1>
                <?= AlertWidget::widget(); ?>
                <div class="pd-bottom-80 pd-top-30">
                    <div class="bg-gray bg-box bg-box--pd-sm">
                        <div class="container-fluid">
                            <div class="row">
                                <?php $form = ActiveForm::begin([
                                    'fieldConfig' => [
                                        'template' => '{label}' . PHP_EOL . '{passwordShow}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    ],
                                    'enableClientValidation' => false,
                                    'options' => [
                                        'id' => 'formChangePass',
                                    ],
                                ]); ?>
                                <form method="post" id="formRecoveryPass">
                                    <div class="col-lg-10">
                                        <div class="error-message"></div>
                                        <p class="form-description text-black">
                                            Пароль должен содержать буквы и цифры.
                                            Минимальная длина пароля - 8 символов.
                                        </p>
                                    </div>
                                    <div class="col-lg-7 col-md-10">
                                        <?= $form->field($model, 'password', [
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                            'parts' => [
                                                '{passwordShow}' => '<i class="password-show"></i>',
                                            ],
                                        ])->passwordInput([
                                            'value' => '',
                                            'id' => 'password',
                                        ]) ?>

                                        <?= $form->field($model, 'password_repeat', [
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                            'parts' => [
                                                '{passwordShow}' => '<i class="password-show"></i>',
                                            ],
                                        ])->passwordInput([
                                            'value' => '',
                                        ]) ?>

                                        <!-- <div class="form-group form-group--placeholder-fix">
                                            <label class="placeholder" for="password_again">Повторите пароль</label>
                                            <i class="password-show"></i>
                                            <input name="password_again" type="password" class="form-control"
                                                   id="password_again">
                                        </div> -->
                                        <div class="two-btn">
                                            <button type="submit" class="btn btn-primary btn-lg two-btn__elem">
                                                Сохранить
                                            </button>
                                        </div>
                                    </div>
                                    <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->render('_toolbar'); ?>
        </div>
    </div>
</section>
<!-- page content end -->