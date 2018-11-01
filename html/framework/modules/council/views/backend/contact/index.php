<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $contactForm \app\modules\council\forms\ContactForm */

$this->title = 'Контактная информация';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <div class="panel panel-default">
            <?php $form = ActiveForm::begin(); ?>
            <div class="panel-heading">
                Контактная информация
            </div>
            <div class="panel-body">
                <?= $form->field($contactForm, 'contact')->widget(
                    Yii::createObject([
                        'class' => \krok\editor\interfaces\EditorInterface::class,
                        'model' => $contactForm,
                        'attribute' => 'contact',
                    ])
                );; ?>
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
