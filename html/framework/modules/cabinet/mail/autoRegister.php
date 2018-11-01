<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 17.07.17
 * Time: 15:11
 */

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\modules\cabinet\models\Client */
/* @var $form \app\modules\cabinet\form\RegistrationWithVerifyForm */

?>
<p>
    Здравствуйте,<br>
    Вы успешно отправили электронное обращение. Для отслеживания статуса обращения вы можете перейти в раздел "Поданные
    обращения" вашего личного кабинета, или перейдя по ссылке.
</p>
<p>
    Ваш пароль - <?= $form->password; ?><br>
    Изменить пароль вы можете в профиле личного кабинета, или
    <a target="_blank" href="<?= Url::to(['/cabinet/default/reset-with-verify'], true); ?>">перейдя по
        ссылке</a>.
</p>
<p>
    С уважением,<br>
    техническая поддержка портала Министерства труда и социальной защиты<br>
    Российской Федерации
</p>