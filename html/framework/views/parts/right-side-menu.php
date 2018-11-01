<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 26.07.2017
 * Time: 10:56
 */

use yii\helpers\Url;

/** @var $this \yii\web\View */

$controller = \Yii::$app->controller;
$module = $controller->module;
$links = [
    [
        'label' => 'Картина дня',
        'url' => Url::to(['/news/news/list']),
        'active' => ($module && 'news' == $module->id) ? true : false,
    ],
    [
        'label' => 'Мероприятия',
        'url' => Url::to(['/events']),
        'active' => ($module && 'events' == $module->id) ? true : false,
    ],
    [
        'label' => 'Медиафайлы',
        'url' => Url::to(['/media/media/index']),
        'active' => ($module && 'media' == $module->id) ? true : false,
    ]
];
?>

<ul class="list-nav">
    <?php foreach ($links as $link): ?>
        <?php $active = isset($link['active']) && $link['active'] == true ? 'active' : '' ?>
        <li class="<?= $active; ?>"><a class="text-dark" href="<?= $link['url'] ?>"><?= $link['label'] ?></a></li>
    <?php endforeach; ?>
</ul>
