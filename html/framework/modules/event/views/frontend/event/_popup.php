<?php
/** @var $accreditation \app\modules\event\forms\AccreditationForm */

use yii\captcha\Captcha;
use yii\widgets\ActiveForm;

\app\modules\event\assets\AccreditationFormAsset::register($this);

?>
<div id="modalAccreditation" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Аккредитация представителей СМИ на мероприятие</h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'action' => ['save-accreditation'],
                    'id' => 'accreditationForm',
                    'options' => [
                        'class' => 'form form-accreditation'
                    ],
                    'enableClientScript' => false,
                ]); ?>
                <div class="hidden">
                    <?= $form->field($accreditation, 'event_id')->hiddenInput()->label(false) ?>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="error-message"></div>
                        <div class="form-block-two">
                            <?= $form->field(
                                $accreditation,
                                'surname',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                            <?= $form->field(
                                $accreditation,
                                'name',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                        </div>
                        <?= $form->field(
                            $accreditation,
                            'middle_name',
                            [
                                'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                'options' => [
                                    'class' => 'form-group form-group--placeholder-fix required',
                                ],
                                'labelOptions' => [
                                    'class' => 'placeholder',
                                ],
                            ]
                        )->textInput(); ?>
                        <div class="form-passport">
                            <div class="form-passport__title text-dark">Паспортные данные</div>
                            <div class="form-passport__box">
                                <div class="form-block-two form-block-two--30">
                                    <?= $form->field(
                                        $accreditation,
                                        'passport_series',
                                        [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix required',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                        ]
                                    )->textInput(); ?>
                                    <?= $form->field(
                                        $accreditation,
                                        'passport_number',
                                        [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix required',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                        ]
                                    )->textInput(); ?>
                                </div>
                                <div class="form-block-two form-block-two--30">
                                    <?= $form->field(
                                        $accreditation,
                                        'passport_burthday',
                                        [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix required',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                        ]
                                    )->textInput(); ?>
                                    <?= $form->field(
                                        $accreditation,
                                        'passport_burthplace',
                                        [
                                            'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                            'options' => [
                                                'class' => 'form-group form-group--placeholder-fix required',
                                            ],
                                            'labelOptions' => [
                                                'class' => 'placeholder',
                                            ],
                                        ]
                                    )->textInput(); ?>
                                </div>
                                <?= $form->field(
                                    $accreditation,
                                    'passport_issued',
                                    [
                                        'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                        'options' => [
                                            'class' => 'form-group form-group--placeholder-fix required',
                                        ],
                                        'labelOptions' => [
                                            'class' => 'placeholder',
                                        ],
                                    ]
                                )->textInput(); ?>
                            </div>
                        </div>
                        <div class="form-block-two">
                            <?= $form->field(
                                $accreditation,
                                'org',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                            <?= $form->field(
                                $accreditation,
                                'job',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                        </div>
                        <?= $form
                            ->field(
                                $accreditation,
                                'accid',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )
                            ->label('Номер аккредитационного удостоверения <span class="span-red">*</span>')
                            ->textInput(); ?>
                        <div class="form-block-two">
                            <?= $form->field(
                                $accreditation,
                                'phone',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                            <?= $form->field(
                                $accreditation,
                                'email',
                                [
                                    'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                    'options' => [
                                        'class' => 'form-group form-group--placeholder-fix required',
                                    ],
                                    'labelOptions' => [
                                        'class' => 'placeholder',
                                    ],
                                ]
                            )->textInput(); ?>
                        </div>
                        <?= $form->field(
                            $accreditation,
                            'base_formation',
                            [
                                'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                'options' => [
                                    'class' => 'sm form-group form-group--placeholder-fix',
                                ],
                                'labelOptions' => [
                                    'class' => 'placeholder',
                                ],
                            ]
                        )->textarea(); ?>
                    </div>
                    <?= $form->field($accreditation, 'verifyCode')->widget(
                        Captcha::className(), [
                        'captchaAction' => '/events/event/captcha',
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
                                <input type="checkbox" value="1" id="inputDeal"/>
                                <span class="placeholder text-black">Разрешаю обработку персональных данных</span>
                            </label>
                        </div>
                        <div class="two-btn">
                            <button id="technicalSupportFormBtnSubmit" disabled="" type="submit" class="btn btn-primary btn-lg two-btn__elem">Отправить
                            </button>
                        </div>
                        <div class="text-black-b pd-top-20">
                            Поля, отмеченные *, обязательны для заполнения<br>
                            Поля, отмеченные <span class="span-red">*</span>, обязательны для заполнения для журналистов иностранных СМИ
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end() ?>
            </div>
        </div>
    </div>
</div>

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
