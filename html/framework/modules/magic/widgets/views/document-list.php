<?php
/* @var $this yii\web\View */
/* @var $model [] */
/* @var $row app\modules\magic\models\Magic */

?>
<?php if ($model) : ?>
    <?php foreach ($model as $type => $arr): ?>
        <?php foreach ($arr as $row): ?>
            <div class="block-douwnload-md hidden-sm hidden-xs">
                <div class="doc-icon">
                    <span class="doc-icon-bg"></span>
                    <span class="format text-uppercase"><?= $row->extension ?></span><br/>
                    <span class="size"><?= Yii::$app->formatter->asShortSize($row->size, 2) ?></span>
                </div>
                <p class="name"><?= $row->label ?></p>
                <a href="<?= $row->getSrcUrl() ?>" class="btn btn-block btn-primary ">Скачать</a>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
<?php endif; ?>




