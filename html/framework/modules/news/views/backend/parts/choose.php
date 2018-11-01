<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2017
 * Time: 13:19
 */


/** @var $this \yii\web\View */
/** @var $files \app\modules\news\helpers\Path[] */


?>
<div class="row">
    <?php foreach ($files as $index => $file): ?>
        <div class="choose-image col-md-3 text-center">
            <img src="<?= $file->getUrlFile() ?>" alt="" class="thumbnail">
        </div>
    <?php endforeach; ?>
</div>