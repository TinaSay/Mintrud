<?php

use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\newsletter\widgets\NewsletterShowAsset;
use app\modules\newsletter\models\Newsletter;
use yii\helpers\Html;

NewsletterShowAsset::register($this);
?>
<h4 class="text-uppercase text-prime pd-bottom-10">Новостная рассылка</h4>
<p class="text-base pd-bottom-10">Актуальная информация Минтруда у Вас на почте.</p>

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
    'action' => Url::to(['/newsletter/default/send']),
    'options' => [
        'class' => 'form form-comment',
        'id' => 'newsletter-form',
    ],
    'enableClientScript' => false,

]); ?>
<?php
/*
 *
 * <div style="display: none;">

<a href="#newsletter-param" class="btn btn-block btn-primary btn-newsletter-param collapsed" data-toggle="collapse" aria-expanded="false">Настройка рассылки <i></i></a>

<div class="collapse text-black" id="newsletter-param">
    <div class="form-groupe aside-form-box">
        <div class="aside-form-title">Укажите переодичность</div>
        <label class="checkbox-box wrap-check">
            <input name="period" checked type="radio">
            <label>Ежедневно</label>
        </label>
        <label class="checkbox-box wrap-check">
            <input name="period" checked type="radio">
            <label>Каждые 3 дня</label>
        </label>
        <label class="checkbox-box wrap-check">
            <input name="period" checked type="radio">
            <label>Еженедельно</label>
        </label>
    </div>
    <div class="form-groupe aside-form-box no-border-bottom">
        <div class="aside-form-title">Выберите разделы</div>
        <label class="checkbox-box wrap-check">
            <input checked type="checkbox">
            <label>Новости</label>
        </label>
        <label class="checkbox-box wrap-check">
            <input type="checkbox">
            <label>Мероприятия</label>
        </label>
    </div>
</div>

</div>

 *
 */
?>
<div>

    <a href="#newsletter-param" class="btn btn-block btn-primary btn-newsletter-param collapsed" data-toggle="collapse"
       aria-expanded="false">Настройка рассылки <i></i></a>

    <div class="collapse text-black" id="newsletter-param">
        <div class="form-group aside-form-box period-wrap">
            <div class="aside-form-title">Укажите периодичность</div>
            <?= Html::activeRadioList($model, 'time', $model->getTimeList(), [
                'item' => function ($index, $label, $name, $checked, $value) {
                    $html = '<label class="checkbox-box wrap-check">';
                    $html .= Html::radio($name, $checked, ['value' => $value]);
                    $html .= Html::label($label);
                    $html .= '</label>';
                    return $html;

                }
            ]); ?>
        </div>
        <div class="form-group aside-form-box no-border-bottom category-wrap">
            <div class="aside-form-title">Выберите разделы</div>
            <label class="checkbox-box wrap-check">
                <?= \yii\helpers\Html::activeCheckbox($model, 'isNews', ['label' => false]) ?>
                <label><?= $model->getAttributeLabel('isNews') ?></label>
            </label>
            <label class="checkbox-box wrap-check">
                <?= \yii\helpers\Html::activeCheckbox($model, 'isEvent', ['label' => false]) ?>
                <label><?= $model->getAttributeLabel('isEvent') ?></label>
            </label>
            <div class="help-block"></div>
        </div>
    </div>

</div>
<?= $form->field($model, 'email')->textInput(); ?>
<div class="error-message"></div>

<button id="newsletter-submit-button" class="btn btn-block btn-primary">Подписаться</button>
<?php ActiveForm::end(); ?>

<div id="modalOk" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content text-center">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Спасибо!</h4>
                <div class="text-black pd-top-10">Вы подписались на новостную рассылку Минтруда.</div>
            </div>
        </div>
    </div>
</div>
