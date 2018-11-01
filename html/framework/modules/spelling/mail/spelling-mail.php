<?php

/** @var $this yii\web\View */
/** @var $model \app\modules\spelling\models\Spelling */

;?>

<h2>На сайте найдена ошибка</h2>

<p>
    <strong>Адрес страницы</strong>:
    <br>
    <a href="<?=$model->url;?>"><?=$model->url;?></a>
</p>
<p>
    <strong>Текст содержащий ошибку</strong>:
    <br>
    <?=$model->selectedText;?>
</p>
<p>
    <strong>Комментарий пользователя</strong>:
    <br>
    <?=$model->comment;?>
</p>
