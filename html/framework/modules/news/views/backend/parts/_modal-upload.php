<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 15.10.17
 * Time: 11:34
 */

use app\assets\BootstrapFileInput;
use app\modules\news\assets\ChooseAsset;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $form \app\modules\news\forms\NewsForm */

BootstrapFileInput::register($this);
ChooseAsset::register($this);

$form->src = $form->model->getThumbUrl('thumb');

?>

<div class="form-group" id="form-group-chosen">
    <div class="row">
        <div class="col-md-3">
            <?= Html::img($form->src, ['class' => 'img-thumbnail']) ?>
            <?= Html::activeHiddenInput($form, 'src'); ?>
        </div>
        <div class="col-md-9">
            <a href="" id="remove-chosen" class="btn">Удалить</a>
        </div>
    </div>
</div>

<?php Modal::begin([
    'toggleButton' => [
        'label' => 'Изображение'
    ],
    'footer' => Html::button('Выбрать', ['class' => 'btn btn-primary btn-sm', 'id' => 'choose']),
    'options' => [
        'id' => 'modal-upload',
    ],
    'size' => Modal::SIZE_LARGE,
]) ?>
<div class="row">
    <div class="col-md-2">
        <?= Html::fileInput(
            'file',
            null,
            [
                'class' => 'file',
                'id' => 'file-upload',
                'data' => [
                    'show-caption' => false,
                    'show-preview' => false,
                    'show-remove' => false,
                    'upload-class' => 'btn btn-sm',
                    'browse-class' => 'btn btn-sm',
                    'browse-label' => '',
                    'upload-label' => '',
                    'show-cancel' => false,
                    'allowed-file-types' => ['image'],
                    'upload-url' => Url::to(['/news/news/upload']),
                ]
            ]
        ); ?>
    </div>
    <div class="col-md-10 preview">
        <div id="choose-images" data-url="<?= Url::to(['choose']) ?>">

        </div>
    </div>

</div>
<?php Modal::end() ?>
