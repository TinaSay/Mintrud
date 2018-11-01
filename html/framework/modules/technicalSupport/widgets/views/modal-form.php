<?php
/**
 * @var $model \app\modules\technicalSupport\models\TechnicalSupport
 */

use app\modules\technicalSupport\widgets\TechnicalSupportWidgetAsset;
use yii\captcha\Captcha;
use yii\widgets\ActiveForm;

TechnicalSupportWidgetAsset::register($this);
?>

<?php $this->beginBlock('modalTechnicalSupport'); ?>
<div id="modalTechnicalSupport" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Напишите в техническую поддержку сайта</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['/technicalSupport/default/send'],
                    'options' => [
                        'class' => 'form form-comment',
                        'id' => 'technicalSupportForm',
                    ],
                    'enableClientScript' => false,
                ]); ?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="error-message"></div>
                        <div class="pd-bottom-10">Оставьте информацию по проблемам и ошибкам в работе сайта</div>
                        <?= $form->field($model, 'theme')
                            ->dropDownList(
                                [
                                    'Проблемы в работе сайта' => 'Проблемы в работе сайта',
                                    'Предложения по совершенствованию сайта' => 'Предложения по совершенствованию сайта',
                                ],
                                [
                                    'class' => 'selectpicker',
                                    'title' => $model->getAttributeLabel('theme'),
                                ]
                            )->label(false);
                        ?>

                        <?= $form->field($model, 'name', [
                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                            'options' => [
                                'class' => 'form-group form-group--placeholder-fix',
                            ],
                            'labelOptions' => [
                                'class' => 'placeholder',
                            ],
                        ])->textInput(); ?>

                        <?= $form->field($model, 'email', [
                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                            'options' => [
                                'class' => 'form-group form-group--placeholder-fix',
                            ],
                            'labelOptions' => [
                                'class' => 'placeholder',
                            ],
                        ])->textInput(); ?>

                        <?= $form->field($model, 'phone', [
                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                            'options' => [
                                'class' => 'form-group form-group--placeholder-fix',
                            ],
                            'labelOptions' => [
                                'class' => 'placeholder',
                            ],
                        ])->textInput(); ?>

                        <?= $form->field($model, 'comment', [
                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                            'options' => [
                                'class' => 'sm form-group form-group--placeholder-fix',
                            ],
                            'labelOptions' => [
                                'class' => 'placeholder',
                            ],
                        ])->textarea(); ?>

                    </div>
                    <?= $form->field($model, 'verifyCode')->widget(
                        Captcha::className(), [
                        'captchaAction' => '/technicalSupport/default/captcha',
                        'template' => '<div class="col-lg-6 captcha-image">{image}</div>' .
                            '<div class="col-lg-6">' .
                            '<div class="form-group form-group--placeholder-fix">' .
                            '<label for="captcha" class="placeholder">Введите код с картинки </label>' .
                            '{input}' .
                            '</div>' .
                            '</div>',
                    ])->label(false) ?>
                    <div class="col-lg-12">
                        <div class="form-group clearfix pd-top-20 pd-bottom-20">
                            <label class="wrap-check">
                                <input type="checkbox" name="<?= $model->formName() . '[deal]'; ?>" value="1"
                                       id="technicalSupportInputDeal"/>
                                <span class="placeholder text-black">Разрешаю обработку персональных данных</span>
                            </label>
                        </div>
                        <div class="two-btn">
                            <button id="technicalSupportFormBtnSubmit" disabled="" type="submit"
                                    class="btn btn-primary btn-lg two-btn__elem">Отправить
                            </button>
                        </div>
                        <div class="text-black-b pd-top-20">Поля, отмеченные *, обязательны для заполнения</div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
<?php $this->endBlock(); ?>
<?php $this->beginBlock('modalOk'); ?>
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
<?php $this->endBlock(); ?>
