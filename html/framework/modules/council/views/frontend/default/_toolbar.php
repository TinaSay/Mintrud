<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 12.07.17
 * Time: 11:15
 */

/* @var $this yii\web\View */

use yii\helpers\Url;

?>
<aside class="main-aside">
    <ul class="list-nav no-mr-top">
        <li<?php if (Yii::$app->controller->action->id == 'index'): ?> class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['index']); ?>">Уведомления</a>
        </li>
        <li<?php if (Yii::$app->controller->action->id == 'change-password'): ?> class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['change-password']); ?>">Изменение пароля</a>
        </li>
    </ul>
</aside>