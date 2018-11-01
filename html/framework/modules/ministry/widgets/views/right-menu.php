<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 19.08.17
 * Time: 12:46
 */

use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $list \app\modules\ministry\models\Ministry[] */
?>
<?php if ($list): ?>
    <ul class="list-nav">
        <?php foreach ($list as $item): ?>
            <?php $active = isset($item['selected']) && $item['selected'] == true ? 'active' : '' ?>
            <li class="<?= $active; ?>">
                <a class="text-dark"
                   href="<?= Url::to('/' . $item['url']); ?>"><?= $item['title'] ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
