<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 19.07.17
 * Time: 18:36
 */

use app\modules\auth\models\Auth;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\auth\models\Profile */
/* @var $form yii\widgets\ActiveForm */
/** @var $type \app\modules\auth\types\ProfileType */
/** @var $ip string */

$this->title = 'Мой профиль';
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <?php $form = ActiveForm::begin(); ?>
        <div class="form-group">
            <div class="help-block">
                Сейчас у вас IP: <?= $ip ?>
                <?php if ($type->model->isBindIp()): ?>
                    Вы привязали IP: <?= $type->model->getIp(); ?>
                <?php endif ?>
            </div>
            <?= $form->field($type, 'bind_ip')->dropDownList(Auth::getBindIpDropDown()) ?>
            <?php if ($type->bind_ip === Auth::BIND_IP_DYNAMIC): ?>
                <?= $form->field($type, 'dynamicIp') ?>
            <?php endif; ?>
            <?= Html::submitButton(Yii::t('system', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true, 'value' => '']) ?>

        <?= $form->field($model, 'password_new')->passwordInput(['maxlength' => true, 'value' => '']) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('system', 'Update'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
