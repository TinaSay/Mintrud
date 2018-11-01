<?php
/* @var $this yii\web\View */
/* @var $model [] */
/* @var $row app\modules\magic\models\Magic */

use app\assets\FancyBoxAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

FancyBoxAsset::register($this);
?>

<?php if (($image = ArrayHelper::getValue($model, 'image')) != []) : ?>
    <div class="panel panel-default">
        <div class="panel-heading">Фотогалерея</div>
        <div class="panel-body">
            <?php foreach ($image as $row) : ?>
                <div class="thumbnail">
                    <?php if ($row->getType() == 'image') : ?>
                        <?= Html::a(
                            Html::img($row->getPreviewUrl(), ['alt' => Html::encode($row->label)]),
                            $row->getSrcUrl(),
                            [
                                'data-pjax' => 0,
                                'class' => 'fancybox',
                                'title' => $row->label,
                            ]
                        ) ?>
                    <?php endif; ?>

                    <div class="caption">
                        <p>
                            <a class="btn btn-default" href="<?= Url::to(
                                ['/magic/default/download', 'id' => $row->id]
                            ) ?>" data-pjax="0"><?= Yii::t('magic', 'Download') ?></a>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<?php if (($application = ArrayHelper::getValue($model, 'application')) != []) : ?>
    <div class="panel panel-default">
        <div class="panel-heading">Документы</div>
        <div class="panel-body">
            <?php foreach ($application as $row) : ?>
                <div class="thumbnail">
                    <div class="caption">
                        <p>
                            <a class="btn btn-default" href="<?= Url::to(
                                ['/magic/default/download', 'id' => $row->id]
                            ) ?>" data-pjax="0"><?= Html::encode($row->label) ?></a>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>
