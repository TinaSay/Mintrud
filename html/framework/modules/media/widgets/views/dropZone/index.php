<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 24.08.17
 * Time: 18:33
 */

/* @var $this yii\web\View */
/* @var $model \app\modules\media\models\Photo */
/* @var $list \krok\storage\dto\StorageDto[] */

/* @var $key string */

use krok\dropzone\storage\widgets\DropzoneStorageWidget;
use yii\bootstrap\Alert;
use yii\helpers\Url;
use yii\web\JsExpression;

\krok\slim\SlimAsset::register($this);

$this->registerJs('jQuery(".slim").slim();');

$listNew = [];
foreach ($list as $item) {
    $listNew[] = $this->render('_item', ['dto' => $item, 'model' => $model]);
}
?>
    <div class="dropzone">
        <?php if (!empty($list)) : ?>
            <?= Alert::widget([
                'options' => [
                    'class' => 'alert-info',
                ],
                'body' => 'Загруженные фотографии.',
            ]) ?>
            <div class="row">
                <?= \yii\jui\Sortable::widget([
                    'items' => $listNew,
                    'options' => [
                        'tag' => 'div',
                        'class' => 'row',
                    ],
                    'itemOptions' => [
                        'tag' => 'div',
                        'class' => 'dz-preview dz-file-preview',
                        'style' => ['width' => '32%']
                    ],
                ]); ?>
            </div>
        <?php endif; ?>
    </div>

<?= DropzoneStorageWidget::widget(['key' => $key]) ?>

<?= Alert::widget([
    'options' => [
        'class' => 'alert-info',
    ],
    'body' => 'Добавление новых фотографий.',
]) ?>
<?= \krok\dropzone\DropzoneWidget::widget([
    'clientOptions' => [
        'url' => Url::to(['upload']),
        'previewTemplate' => $this->render('_template.php'),
        'acceptedFiles' => 'image/jpeg, image/pjpeg, image/png, image/gif',
        'dictDefaultMessage' => 'Кликните или перетащите файл на выделенную область',
        'parallelUploads' => 1,
    ],
    'clientEvents' => [
        'success' => new JsExpression('function(file, json) { this.emit("thumbnail", file, json.publicUrl); jQuery(".slim").slim(); }'),
    ],
]) ?>