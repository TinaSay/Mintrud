<?php

use app\modules\reception\services\SendAppealService;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $settingsForm \app\modules\reception\form\SettingsForm */

$this->title = Yii::t('system', 'Appeals text');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <div class="panel panel-default">
            <?php $form = ActiveForm::begin(); ?>
            <div class="panel-body">

                <?= $form->field($settingsForm, 'textBefore')->widget(
                    Yii::createObject([
                        'class' => \krok\editor\interfaces\EditorInterface::class,
                        'model' => $settingsForm,
                        'attribute' => 'textBefore',
                    ])
                ); ?>

                <?= $form->field($settingsForm, 'textRight')->widget(
                    Yii::createObject([
                        'class' => \krok\editor\interfaces\EditorInterface::class,
                        'model' => $settingsForm,
                        'attribute' => 'textRight',
                    ])
                ); ?>

                <?= $form->field($settingsForm, 'debug')->radioList([
                    SendAppealService::DEBUG_NONE => 'Не логировать',
                    SendAppealService::DEBUG_LOG => 'Логировать данные обращений',
                    SendAppealService::DEBUG_FILES => 'Логировать данные обращений и сохранять файлы',
                ]); ?>

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
