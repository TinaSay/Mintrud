<?php

/** @var $this yii\web\View */
/** @var $model \app\modules\technicalSupport\models\TechnicalSupport */; ?>

<h2>Техническая поддержка</h2>

<p>
    <strong><?= $model->getAttributeLabel('theme'); ?></strong>:
    <br>
    <?= $model->theme; ?>
</p>
<p>
    <strong><?= $model->getAttributeLabel('name'); ?></strong>:
    <br>
    <?= $model->name; ?>
</p>
<p>
    <strong><?= $model->getAttributeLabel('email'); ?></strong>:
    <br>
    <?= $model->email; ?>
</p>
<p>
    <strong><?= $model->getAttributeLabel('phone'); ?></strong>:
    <br>
    <?= $model->phone; ?>
</p>
<p>
    <strong><?= $model->getAttributeLabel('comment'); ?></strong>:
    <br>
    <?= $model->comment; ?>
</p>