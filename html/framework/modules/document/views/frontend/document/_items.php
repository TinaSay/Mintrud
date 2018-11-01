<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.08.17
 * Time: 15:39
 */

/** @var $models \app\modules\document\models\Document[] */
/** @var $pagination \yii\data\Pagination */

?>
<?php if (!empty($models)): ?>
    <?php foreach ($models as $model): ?>
        <div class="post-list">
            <p class="page-date text-light"><?= $model->asDate() ?></p>
            <a class="text-black" href="<?= $model->getUrl() ?>">
                <p class="post-name"><?= $model->title ?></p>
                <p><?= $model->announce ?></p>
            </a>
        </div>
    <?php endforeach; ?>
    <div class="wrap-pagination">
        <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
    </div>

<?php else: ?>
    <p>Документы не найдены</p>
<?php endif; ?>