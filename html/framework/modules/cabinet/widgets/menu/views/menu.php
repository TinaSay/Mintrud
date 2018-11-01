<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.07.17
 * Time: 12:30
 */

/* @var $this yii\web\View */
/* @var $active string */

/* @var $user app\modules\cabinet\models\Client */

use yii\helpers\Url;

?>
<div class="border-block aside-user-header">
    <div class="aside-user-header__inner">
        <i></i><?= $user->email ?>
    </div>
</div>
<ul class="list-nav no-mr-top">
    <li <?php if ($active === 'cabinet/reception/reception/index') : ?>class="active"<?php endif; ?>>
        <a class="text-black" href="<?= Url::to(['/cabinet/reception']) ?>">Обращения</a>
    </li>
    <li <?php if ($active === 'cabinet/favorite/default/index') : ?>class="active"<?php endif; ?>>
        <a class="text-black" href="<?= Url::to(['/cabinet/favorite']) ?>">Избранное</a>
    </li>
    <li <?php if ($active === 'cabinet/view/index') : ?>class="active"<?php endif; ?>>
        <a class="text-black" href="<?= Url::to(['/cabinet/view/index']) ?>">Профиль</a>
    </li>
    <?php if (!$user->hasSocial('esia')): ?>
        <li <?php if ($active === 'cabinet/view/socials') : ?>class="active"<?php endif; ?>>
            <a class="text-black" href="<?= Url::to(['/cabinet/view/socials']) ?>">
                Активация учетной записи Портала Госуслуг
            </a>
        </li>
    <?php endif; ?>
    <li>
        <a class="text-black" href="<?= Url::to(['/cabinet/view/logout']) ?>">Выйти</a>
    </li>
</ul>
