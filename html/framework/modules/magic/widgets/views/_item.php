<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 07.03.15
 * Time: 14:57
 */

/* @var $this yii\web\View */
/* @var $row app\modules\magic\models\Magic */

use yii\helpers\Html;
use yii\helpers\Url;

?>
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
        <div class="form-group">
            <?= Html::activeInput(
                'text',
                $row,
                'label',
                [
                    'name' => $row->formName() . '[' . $row->id . '][label]',
                    'class' => 'form-control',
                    'placeholder' => Yii::t('magic', 'Label'),
                ]
            ) ?>
            <br>
            <?= Html::activeInput(
                'text',
                $row,
                'hint',
                [
                    'name' => $row->formName() . '[' . $row->id . '][hint]',
                    'class' => 'form-control',
                    'placeholder' => Yii::t('magic', 'Hint'),
                ]
            ) ?>
            <?= Html::activeInput(
                'hidden',
                $row,
                'position',
                ['name' => $row->formName() . '[' . $row->id . '][position]', 'class' => 'form-control']
            ) ?>
        </div>
        <p>
            <a class="btn btn-danger" href="<?= Url::to(
                ['/magic/manage/delete', 'id' => $row->id]
            ) ?>" data-pjax="1"><?= Yii::t('yii', 'Delete') ?></a>

            <?= Html::submitButton(Yii::t('yii', 'Update'), ['class' => 'btn btn-primary']); ?>

            <a class="btn btn-default" href="<?= Url::to(
                ['/magic/manage/download', 'id' => $row->id]
            ) ?>" data-pjax="0"><?= Yii::t('magic', 'Download') ?></a>
        </p>

        <p>
            <span class="label label-info"><?= Yii::t('magic', 'Created at') ?>:
                <?= Yii::$app->getFormatter()->asDatetime($row->created_at) ?>
            </span>
        </p>

        <p>
            <span class="label label-info"><?= Yii::t('magic', 'Updated at') ?>:
                <?= Yii::$app->getFormatter()->asDatetime($row->updated_at) ?>
            </span>
        </p>
    </div>
</div>
