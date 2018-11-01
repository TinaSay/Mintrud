<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 31.07.2017
 * Time: 14:25
 */

use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var \app\modules\cabinet\models\Client $user */
$user = Yii::$app->user->getIdentity();
?>

<div class="wiget-demo hidden-xs hidden-sm">
    <div class="nav-container">
        <ul class="wiget-list">
            <li>
                <div class="btn-group">
                    <button type="button" class="btn-lang dropdown-toggle" data-toggle="dropdown">Ru</button>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="/">Ru</a></li>
                        <li><a href="/eng">Eng</a></li>
                    </ul>
                </div>
            </li>
            <?php if(Yii::$app->user->getIsGuest()):?>
            <li><a href="<?= Url::to(Yii::$app->user->loginUrl); ?>">
                    <img src="/static/default/img/icon/user.svg">Личный кабинет</a></li>
            <?php else:?>
                <li><a href="<?= Url::to(Yii::$app->user->loginUrl); ?>">
                        <img src="/static/default/img/icon/user.svg"><?= $user->email ?: 'Личный кабинет';?></a></li>
            <?php endif;?>
            <li><a href="<?= Url::to(['/reception/form']); ?>"><img src="/static/default/img/icon/inquiry-head.svg">Подать
                    обращение</a></li>
            <li><span class="btn-blind open-modal-blind"><img src="/static/default/img/icon/blind.svg">Версия для слабовидящих</span>
            </li>
        </ul>
    </div>
</div>
