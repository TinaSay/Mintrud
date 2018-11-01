<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 18:46
 */
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models \app\modules\event\models\Event[] */

?>
<?php if (!empty($models)): ?>
    <h3 class="page-sub-head text-dark">Материалы по теме</h3>
    <div class="row flax-wrap">
        <?php foreach ($models as $model) : ?>
        <div class="col-sm-6 col-md-4 mr-bottom-30">
            <a href="<?= Url::to(['/events/event/view', 'id' => $model->id]) ?>" class="border-block-sm">
                <p><span class="news-date text-light"><?= $model->asDate(); ?></span></p> 
                <p class="text-dark"><?= $model->title ?></p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>