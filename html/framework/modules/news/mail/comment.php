<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

/* @var $form \app\modules\opendata\forms\CommentForm */

?>
<p>
    Пользователь <?= Html::encode($form->name); ?> (<?= Html::encode($form->email); ?>) отправил вопрос в секретариат Совета

</p>
<p>
    Комментарий:<br/>
    <?= nl2br(Html::encode($form->comment)); ?>
</p>

