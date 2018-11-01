<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:14
 */

use app\components\helpers\StringHelper;
use app\modules\cabinet\assets\ValidCodeAsset;
use app\modules\reception\assets\AppealAsset;
use app\modules\reception\models\Appeal;
use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var $this yii\web\View */
/** @var $textBefore string */
/** @var $textRight string */
/** @var $model \app\modules\reception\form\AppealForm */
/** @var $appeal Appeal */

ValidCodeAsset::register($this);
AppealAsset::register($this);

$this->title = Yii::t('system', 'Appeals');

$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($textBefore), 255),
], 'og:description');

?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black">Форма отправки обращения</h1>
        <div class="pd-bottom-80 pd-top-30">
            <?php if ($textBefore): ?>
                <div class="post-content text-black pd-bottom-35">
                    <?= $textBefore; ?>
                </div>
            <?php endif; ?>
            <div class="bg-gray bg-box">
                <div class="container-fluid">
                    <div class="row">
                        <?php /*if (Yii::$app->user->getIsGuest()): ?>
                            <div class="col-xs-12 pd-bottom-45">
                                <div class="form-sub-title-type-2 text-dark mr-bottom-5">Авторизуйтесь через ЕСИА</div>
                                <div class="text-dark">Для отправки обращения войдите через учётную запись портала
                                    Госуслуг
                                    или заполните данные с помощью специальной формы ниже
                                </div>
                                <a href="<?= Url::to(['/cabinet/default/oauth', 'authclient' => 'esia']) ?>"
                                   class="btn btn-primary btn-lg link-auth-gos"><span><i></i>Войти через Портал Госуслуг</span></a>
                            </div>
                        <?php endif; */?>
                        <?= AlertWidget::widget(); ?>
                        <?php if ($model->hasErrors()): ?>
                            <?= Html::errorSummary($model, ['class' => 'alert alert-danger']); ?>
                        <?php endif; ?>
                        <?php $form = ActiveForm::begin([
                            'fieldConfig' => [
                                'template' => '{label}' . PHP_EOL . '{input}' . PHP_EOL . '{error}',
                                'options' => [
                                    'class' => 'form-group form-group--placeholder-fix',
                                ],
                                'labelOptions' => [
                                    'class' => 'placeholder',
                                ],
                            ],
                            'enableClientScript' => false,
                            'options' => [
                                'id' => 'formAppeal',
                                'class' => 'form registration-with-verify-form',
                                'enctype' => 'multipart/form-data',
                            ],
                        ]); ?>
                        <div class="col-lg-12">
                            <div class="error-message"></div>
                            <div class="form-sub-title-type-2 text-dark mr-bottom-5">Форма отправки обращения</div>
                            <div class="post-content text-black pd-bottom-15">
                                Поля, отмеченные *, обязательны для заполнения
                            </div>
                            <?= $form->field($model, 'lastName')
                                ->textInput(['readonly' => $model->getLastName() && !Yii::$app->user->getIsGuest()]); ?>

                            <?= $form->field($model, 'firstName')
                                ->textInput(['readonly' => $model->getFirstName() && !Yii::$app->user->getIsGuest()]); ?>

                            <?= $form->field($model, 'secondName')
                                ->textInput(['readonly' => $model->getSecondName() && !Yii::$app->user->getIsGuest()]); ?>

                            <?= $form->field($model, 'theme')
                                ->dropDownList($model::getThemesAsDropDown(),
                                    [
                                        'class' => 'selectpicker',
                                        'title' => $model->getAttributeLabel('theme'),
                                    ])->label(false);
                            ?>
                            <?= $form->field($model, 'status')
                                ->dropDownList($model::getSocialStatusAsDropDown(),
                                    [
                                        'class' => 'selectpicker',
                                        'title' => $model->getAttributeLabel('status'),
                                    ])->label(false);
                            ?>
                            <div class="form-sub-title text-black">Вариант получения ответа</div>
                            <div class="form-group clearfix">
                                <label class="wrap-check">
                                    <?= Html::radio($model->formName() . '[reply]', false, [
                                        'label' => false,
                                        'class' => 'reply postal',
                                        'value' => Appeal::TYPE_POSTAL,
                                    ]); ?>
                                    <span class="placeholder text-black">почтовое отправление</span>
                                </label>
                                <label class="wrap-check">
                                    <?= Html::radio($model->formName() . '[reply]', true, [
                                        'class' => 'reply email',
                                        'value' => Appeal::TYPE_EMAIL,
                                    ]); ?>
                                    <span class="placeholder text-black">электронная почта</span>
                                </label>
                            </div>
                        </div>
                        <!-- если по эл. полчте -->
                        <div data-name="email" class="block-email-or-mail block-if-email active">
                            <div class="col-lg-12">
                                <div class="form-group form-group--placeholder-fix mr-top-15">
                                    <label class="placeholder" for="inputEmail">Email</label>
                                    <div class="form-group--field-btn">
                                        <?php if (Yii::$app->get('user')->getIsGuest()
                                            && !$appeal->email) : ?>
                                            <?= Html::activeInput('email', $model, 'email', [
                                                'class' => 'form-control form-group--field-btn__elem email-input',
                                                'label' => false,
                                            ]); ?>
                                            <div class="form-group--field-btn__elem">

                                                <button data-url="<?= Url::to(['send-verify-code']); ?>" id="sendCode"
                                                        disabled="disabled" type="button"
                                                        class="btn btn-primary btn-send-code btn-lg disabled form-group--field-btn__elem-btn">
                                                    Подтвердить
                                                </button>
                                                <div style="display: none;"
                                                     class="email-accept form-group--field-btn__elem-btn">
                                                    Адрес<br>подтверждён
                                                </div>

                                            </div>
                                        <?php else: ?>
                                            <?= Html::activeInput('email', $model, 'email', [
                                                'class' => 'form-control form-group--field-btn__elem email-input',
                                                'label' => false,
                                                'readonly' => true,
                                                'data-confirmed' => 1,
                                            ]); ?>
                                            <div class="form-group--field-btn__elem">
                                                <div class="email-accept form-group--field-btn__elem-btn">
                                                    Адрес<br>подтверждён
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- эту часть отображаем, если мейл прошел проверку -->
                                <div id="codeContainer" class="form-group code-container" style="display: none;">
                                    <label class="placeholder-email-confirm placeholder">
                                        <span class="bold-md text-black">Введите полученный по почте код</span>
                                        <?= Html::a('Отправить новый код.', ['/cabinet/default/retry-verify-codes'], [
                                            'id' => 'retry-verify-codes-button',
                                            'class' => 'send-new-code btn-send-new-code',
                                            'data-ajax' => true,
                                        ]) ?>
                                    </label>
                                    <div data-action="confirm-email" class="confirm-email">
                                        <div class="confirm-email-code-info">
                                            <ul class="confirm-email__code">
                                                <li class="confirm-email__elem">
                                                    <input data-id="1" maxlength="1" type="text"
                                                           class="form-control">
                                                </li>
                                                <li class="confirm-email__elem">
                                                    <input data-id="2" maxlength="1" type="text"
                                                           class="form-control">
                                                </li>
                                                <li class="confirm-email__elem">
                                                    <input data-id="3" maxlength="1" type="text"
                                                           class="form-control">
                                                </li>
                                                <li class="confirm-email__elem">
                                                    <input data-id="4" maxlength="1" type="text"
                                                           class="form-control">
                                                </li>
                                            </ul>
                                            <input type="hidden" data-url="<?= Url::to(['verify-code']); ?>"
                                                   id="verifycodeform-code" class="form-control verify-code-input"
                                                   value=""/>
                                        </div>
                                        <div class="confirm-email__error"></div>
                                        <div class="confirm-email__result">
                                            <p>Email <strong></strong> подтвержден.</p>
                                        </div>
                                        <div class="hidden retry-verify-codes-runner"
                                             id="retry-verify-codes-runner"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end если по эл. полчте -->

                        <!-- если почтой  -->
                        <div data-name="mail" class="block-email-or-mail block-if-mail indent-col-9">
                            <div class="col-lg-4">
                                <?= $form->field($model, 'country')
                                    ->dropDownList($model::getCountryAsDropDown(),
                                        [
                                            'class' => 'selectpicker country',
                                            'title' => $model->getAttributeLabel('country'),
                                            'data-url' => Url::to(['regions']),
                                        ])->label(false);
                                ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'index')->textInput(['maxlength' => 6]); ?>
                            </div>
                            <div class="col-lg-12">
                                <?= $form->field($model, 'region')
                                    ->dropDownList($model::getRegionAsDropDown($model->getCountry()),
                                        [
                                            'class' => 'selectpicker region',
                                            'title' => $model->getAttributeLabel('region'),
                                        ])->label(false);
                                ?>
                                <?= $form->field($model, 'district')->textInput(); ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'cityType')
                                    ->dropDownList($model::getCityTypeAsDropDown(),
                                        [
                                            'class' => 'selectpicker',
                                            'title' => $model->getAttributeLabel('cityType'),
                                            'value' => $model->getCityTypeCode(),
                                        ])->label(false);
                                ?>
                            </div>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'city')->textInput(); ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'streetType')
                                    ->dropDownList($model::getStreetTypeAsDropDown(),
                                        [
                                            'class' => 'selectpicker',
                                            'title' => $model->getAttributeLabel('streetType'),
                                            'value' => $model->getStreetTypeCode(),
                                        ])->label(false);
                                ?>
                            </div>
                            <div class="col-lg-8">
                                <?= $form->field($model, 'street')->textInput(); ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'house')->textInput(); ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'block')->textInput(); ?>
                            </div>
                            <div class="col-lg-4">
                                <?= $form->field($model, 'flat')->textInput(); ?>
                            </div>
                        </div>
                        <!-- end если почтой  -->

                        <div class="col-lg-12 files-container">
                            <div class="text-black post-content pd-top-25 pd-bottom-30">
                                В целях объективного и всестороннего рассмотрения Вашего обращения в
                                установленные сроки <span class="bold">необходимо</span> в тексте обращения
                                указывать адрес описанного Вами места действия, факта или события, а также
                                <span class="bold">можно</span> указать телефон для возможного уточнения
                                содержания Вашего обращения.
                            </div>
                            <div class="form-group form-group--placeholder-fix placeholder-fix-textarea">
                                <label class="placeholder" for="inputText">
                                    <?= $model->getAttributeLabel('text') ?>
                                    <span class="placeholder__add">не более 2000 символов <span
                                                class="placeholder__add-counter">(осталось <i>2000</i>)</span></span>
                                </label>
                                <?= Html::activeTextarea($model, 'text', [
                                        'maxlength' => true,
                                        'label' => false,
                                        'class' => 'form-control',
                                    ]
                                ); ?>
                            </div>

                            <ul id="files" class="email-list email-list--top text-black">
                                <?php if (!$appeal->isNewRecord && $appeal->files): ?>
                                    <?= Html::input('hidden', 'id', $appeal->id); ?>
                                    <?php foreach ($appeal->files as $key => $attachment): ?>
                                        <li data-size="<?= $attachment->getSize(); ?>" class="old-attachment">
                                            <span class="email-list__email">
                                                <span><?= $attachment->name; ?></span>
                                                <a href='#' type='button' class='delete link-delete'></a>
                                            </span>
                                            <?= Html::input('hidden', 'attachments[]',
                                                $attachment->id
                                            ); ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </ul>

                            <div class="two-btn pd-top-35">
                                <?= Html::input('file', $model->formName() . '[attachments][]', null, [
                                    'class' => 'document-file-input file file--btn two-btn__elem fileupload',
                                    'id' => 'appeal-attachment',
                                    'data-browse' => 'Добавить документ',
                                    'data-size-limit' => \app\modules\reception\form\AppealForm::FILE_SIZE_LIMIT,
                                    'accept' => '.docx, .doc, .xls, .xlsx, .ppt, .pptx, .rtf, .txt, .pdf, .jpeg, .jpg, .bmp, .png, .tiff, .tif, .odt, .ods, .odp',
                                ]); ?>
                            </div>
                            <div class="text-black post-content pd-top-35 pd-bottom-30">
                                Вы можете приложить дополнительные документы или материалы в электронной
                                форме, более полно раскрывающие суть Вашего обращения. Размер файла вложения
                                не может превышать 5 Мб. Для вложений допустимы следующие форматы файлов:
                                docx, doc, xls, xlsx, ppt, pptx, rtf, txt, pdf, jpeg, jpg, bmp, png, tiff, tif, odt,
                                ods, odp.
                            </div>
                            <div class="form-group clearfix pd-bottom-20">
                                <label class="wrap-check">
                                    <input type="checkbox" name="<?= $model->formName() . '[deal]'; ?>" value="1"
                                           id="inputDeal"/>
                                    <span class="placeholder text-black">С порядком приема и рассмотрения обращений ознакомлен(а)</span>
                                </label>
                            </div>
                            <div class="two-btn">
                                <button id="recourseFormBtnSubmit"<?php if (!$model->deal): ?> disabled
                                <?php endif; ?>
                                        type="submit"
                                        class="btn btn-primary btn-lg two-btn__elem">Отправить
                                </button>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <aside class="main-aside">
        <ul class="list-nav no-mr-top">
            <li class="active">
                <a class="text-black" href="/reception/form">Форма отправки обращения</a>
            </li>
            <li><a class="text-black" href="/reception/order">Порядок приема и рассмотрения обращений граждан</a></li>
            <li><a class="text-black" href="/reception/reviews">Обзоры обращений граждан</a></li>
            <li><a class="text-black" href="/reception/law">Нормативно-правовая база</a></li>
            <li><a class="text-black" href="/reception/offline">Порядок и время личного приема граждан</a></li>
        </ul>
        <?php if ($textRight): ?>
            <div class="border-block block-arrow block-arrow--top-title fix-block-aside">
                <?= $textRight; ?>
            </div>
        <?php endif; ?>
    </aside>
</div>
