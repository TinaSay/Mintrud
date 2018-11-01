<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 14:50
 */

use yii\helpers\Url;

/** @var $this \yii\web\View */

?>
<div class="navbar-collapse collapse pull-right pd-right-0" id="navbar-main">
    <ul class="nav navbar-nav">
        <li<?php if(Yii::$app->controller->id == 'discussion'):?> class="active"<?php endif;?>><a href="<?= Url::to(['/lk/discussion/index']); ?>">Обсуждения</a></li>
        <li<?php if(Yii::$app->controller->id == 'default'):?> class="active"<?php endif;?>><a href="<?= Url::to(['/lk/default/index']); ?>">Настройки</a></li>
        <li><a href="<?= Url::to(['/lk/default/logout']) ?>">Выйти</a></li>
    </ul>
</div>
<div class="header-user pull-right"><?= Yii::$app->get('lk')->getIdentity()->name; ?></div>
