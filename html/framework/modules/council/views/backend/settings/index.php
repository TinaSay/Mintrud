<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $settingsForm \app\modules\council\forms\SettingsForm */

$this->title = 'Настройки';
$this->params['breadcrumbs'][] = $this->title;

\app\themes\paperDashboard\assets\BootstrapDateTimePickerAsset::register($this);
$this->registerJs('
    $(".time-picker").datetimepicker({
        format: "HH:mm"
    });
')
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <div class="panel panel-default">
            <?php $form = ActiveForm::begin(); ?>
            <div class="panel-heading">
                Конфигурация рассылки результатов голосования
            </div>
            <div class="panel-body">

                <?= $form->field($settingsForm, 'email')->textInput(); ?>

                <?= $form->field($settingsForm, 'period')->dropDownList($settingsForm::getPeriodList()); ?>

            </div>
            <div class="panel-heading">
                Конфигурация рассылки для новых обсуждений
            </div>
            <div class="panel-body">

                <?= $form->field($settingsForm, 'subscribeTime')->textInput(['class' => 'form-control time-picker']); ?>

            </div>
            <div class="panel-footer">
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('system', 'Update'),
                        ['class' => 'btn btn-primary']
                    ); ?>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
