<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 20:18
 */

use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $list \app\modules\testing\models\Testing[] */
/** @var $pagination \yii\data\Pagination */

$this->title = 'Тестирование';

$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <?php if (!empty($list)): ?>
            <?php foreach ($list as $model): ?>
                <div class="post-list">
                    <p class="page-date text-light"><?= $model->asDate() ?></p>
                    <a class="post-name text-black" href="<?= Url::to(['view', 'id'=>$model->id])?>"><?= $model->title ?></a>
                </div>
            <?php endforeach; ?>
            <div class="wrap-pagination">
                <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
            </div>
        <?php endif; ?>
    </div>
    <aside class="main-aside pd-left-48">
    </aside>
</div>
