<?php
/* @var $this yii\web\View */
/* @var $model [] */
/* @var $row app\modules\magic\models\Magic */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if ($model) : ?>
    <div class="clearfix">
        <?php foreach ($model as $type => $arr): ?>
            <?php foreach ($arr as $row): ?>
                <div class="block-douwnload-md hidden-sm hidden-xs mr-top-40 mr-bottom-50">
                    <div class="doc-icon">
                        <span class="doc-icon-bg"></span>
                        <span class="format"><?= strtoupper($row->extension); ?></span><br/>
                        <span class="size"><?= Yii::$app->formatter->asShortSize($row->size, 1); ?></span>
                    </div>
                    <p class="name"><?= Html::encode($row->label) ?></p>
                    <a href="<?= Url::to(
                        ['/magic/default/download', 'id' => $row->id]
                    ) ?>" class="btn btn-block btn-primary " download="download">Скачать</a>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
