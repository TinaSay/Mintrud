<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 09.12.17
 * Time: 17:37
 */

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $models \app\modules\media\models\Photo[]
 */

if ($models) :?>
    <div id="media_content_photo" class="row media-block custom-tabs-content">
        <ul class="media-photo-preview-list">
            <?php foreach ($models as $model): ?>
                <?php foreach ($model->images as $key => $image):
                    $caption = Html::encode($image->getHint() ? $image->getHint() : ' ');
                    if($image->getUrl()) {
                        $caption = "<a href='{$image->getUrl()}' target='_blank'>$caption</a>";
                    }
                    if ($model->created_at) {
                        $caption .= " <span class='date'>" . Yii::$app->formatter->asDate($model->created_at) . "</span>";
                    }
                    ?>
                    <li<?php if ($key > 0): ?> class="hidden"<?php endif; ?>>
                        <a data-fancybox="gallery-<?= $model->id ?>" href="<?= $model::getImage($image); ?>"
                           class="link-media-photo-modal"
                           data-caption="<?= $caption; ?>" style="background-image: url('<?= $model::getPreviewImage($image); ?>')">
                        </a>
                    </li>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </ul>
        <div class="btn-media btn-media--center col-xs-12">
            <a class="btn btn-gray" href="<?= Url::to(['/media#tab_media_photo']) ?>">Перейти ко всем фотоматериалам</a>
        </div>
    </div>
<?php endif; ?>