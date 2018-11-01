<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 26.07.2017
 * Time: 10:56
 */

use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $active string */

$links = [
    [
        'label' => 'О Министерстве',
        'url' => Url::to(['/ministry/about']),
        'active' => $active === 'about' ? true : false,
    ],
    [
        'label' => 'Деятельность',
        'url' => Url::to(['/ministry/programms']),
        'active' => $active === 'programms' ? true : false,
    ],
    [
        'label' => 'Открытое министерство',
        'url' => Url::to(['/ministry/opengov']),
        'active' => $active === 'opengov' ? true : false,
    ],
    [
        'label' => 'Госслужба в министерстве',
        'url' => Url::to(['/ministry/govserv/conditions']),
        'active' => $active === 'govserv' ? true : false,
    ],
    [
        'label' => 'Противодействие коррупции',
        'url' => Url::to(['/ministry/anticorruption']),
        'active' => $active === 'anticorruption' ? true : false,
    ],
    [
        'label' => 'Конкурсы и тендеры',
        'url' => Url::to(['/ministry/tenders']),
        'active' => $active === 'tenders' ? true : false,
    ],
    [
        'label' => 'Бюджет',
        'url' => Url::to(['/ministry/budget']),
        'active' => $active === 'budget' ? true : false,
    ],
    [
        'label' => 'Международное сотрудничество',
        'url' => Url::to(['/ministry/inter']),
        'active' => $active === 'inter' ? true : false,
    ],
];
?>

<ul class="list-nav">
    <?php foreach ($links as $link): ?>
        <?php $active = isset($link['active']) && $link['active'] == true ? 'active' : '' ?>
        <li class="<?= $active; ?>"><a class="text-dark" href="<?= $link['url'] ?>"><?= $link['label'] ?></a></li>
    <?php endforeach; ?>
</ul>
