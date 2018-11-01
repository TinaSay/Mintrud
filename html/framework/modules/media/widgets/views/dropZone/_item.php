<?php

/**
 * @var $dto \app\modules\media\dto\StorageDto
 * @var $model \app\modules\media\models\Photo
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<?= Html::beginTag('div', [
    'class' => 'dz-details slim form-group',
    'data' => [
        'service' => Url::to(['upload']),
        'default-input-name' => 'file',
        'meta-id' => $dto->getId(),
    ],
]) ?>
<?= Html::img($dto->getPublicUrl(), ['style' => 'max-height: 200px;']) ?>
<?= Html::endTag('div') ?>
<div class="form-group">
    <?= Html::textarea($model->formName() . '[hints][' . $dto->getId() . ']', $dto->getHint(),
        [
            'class' => 'form-control',
            'placeholder' => 'Описание фотографии',
        ]
    ) ?>
</div>
<div class="form-group">
    <?= Html::textInput($model->formName() . '[urls][' . $dto->getId() . ']', $dto->getUrl(),
        [
            'class' => 'form-control',
            'placeholder' => 'Url фотографии',
        ]
    ) ?>
</div>
<div class="form-group">
    <?= Html::a('Скачать', $dto->getPublicUrl(),
        [
            'class' => 'btn btn-primary',
            'style' => 'width:120px;',
            'download' => true,
        ]) ?>
    <?= Html::a('Удалить', ['delete-img', 'id' => $dto->getId(), 'photoId' => $model->id],
        [
            'class' => 'btn btn-primary',
            'style' => 'width:120px;',
            'data' => ['confirm' => 'Вы действительно хотите удалить изображение?'],
        ]) ?>
</div>
