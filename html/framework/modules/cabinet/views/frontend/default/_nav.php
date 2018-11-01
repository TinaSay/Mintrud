<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.07.17
 * Time: 13:27
 */

/* @var $this yii\web\View */
/* @var $active string */

use yii\helpers\Url;

?>
<aside class="main-aside">
    <ul class="list-nav no-mr-top">
        <li <?php if ($active === 'login') : ?>class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['login-with-email']) ?>">Авторизация</a>
        </li>
        <li <?php if ($active === 'registration') : ?>class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['registration-with-verify']) ?>">Регистрация</a>
        </li>
        <li <?php if ($active === 'reset') : ?>class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['reset-with-verify']) ?>">Восстановление доступа</a>
        </li>
    </ul>
</aside>
