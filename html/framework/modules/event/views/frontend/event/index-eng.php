<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 18:05
 */
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models \app\modules\event\models\Event[] */
/** @var $pagination \yii\data\Pagination */

$this->title = 'Events';

$this->params['breadcrumbs'] = [];

?>

<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <?php foreach ($models as $model): ?>
            <div class="post-list">
                <p class="page-date text-light"><?= $model->asDate() ?></p>
                <a class="post-name text-black"
                   href="<?= Url::to(['/events/event/view', 'id' => $model->id, 'language' => 'eng']) ?>"><?= $model->title ?></a>
            </div>
        <?php endforeach; ?>
        <div class="wrap-pagination">
            <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
        </div>
    </div>
    <aside class="main-aside" style="display: none;">
        <?= $this->render('//parts/right-side-menu') ?>
    </aside>
</div>