<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 16:21
 */

/** @var $this \yii\web\View */
/** @var $directions \app\modules\document\models\Direction[] */
/** @var $description \app\modules\document\models\DescriptionDirectory */
/** @var $context \app\modules\document\widgets\DirectionListWidget */

$context = $this->context;


?>

<ul class="list-nav list-nav--light hidden-xs hidden-sm no-mr-top">
    <li class="<?= null === $context->getActive() ? 'active' : null; ?>"><a class="text-black"
                                                                            href="<?= $description->getUrl() ?>">Все
            темы <span class="amount"><?= $context->getCountAll() ?></span></a></li>
    <?php foreach ($directions as $direction): ?>
        <li class="<?= $direction->id === $context->getActive() ? 'active' : null; ?>">
            <a class="text-black" href="<?= $direction->getUrl() ?>"><?= $direction->title ?><span
                        class="amount"> <?= $context->getCount($direction->id); ?></span></a>
        </li>
    <?php endforeach; ?>
</ul>
