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
    <div class="col-md-12">
        <div class="row">
            <?php foreach ($files as $file): ?>
                <div class="col-md-3">
                    <img src="<?= $file->getUrlFile() ?>" class="img-thumbnail" alt="">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>




