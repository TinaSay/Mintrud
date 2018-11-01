<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;
use app\modules\favorite\widgets\AddFavoriteWidget;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $model \app\modules\testing\models\Testing */

$this->title = $model->title;

$this->params['breadcrumbs'] = [
    ['label' => 'Тестирование', 'url' => '/testing/default/index'],
    ['label' => $model->title],

];
$this->params['share-page'] = true;

$this->beginBlock('add-favorite');
echo AddFavoriteWidget::widget(
    [
        'addView' => 'breadcrumbs/add-favorite',
        'existView' => 'breadcrumbs/exist-favorite',
    ]
);
$this->endBlock();

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->description), 255),
], 'og:description');
?>
<div class="clearfix">
    <div class="main pd-bottom-80">
        <h1 class="page-title text-black mr-bottom-60"><?= $model->title ?></h1>
        <div class="pd-bottom-40">
            <?php if ($model->timer > 0): ?>
                <p class="page-date text-light">Время на выполнение: <?= $model->asTime(); ?></p>
            <?php endif; ?>
            <div class="post-content text-dark">
                <?= $model->description ?>
            </div>
        </div>
        <div class="text-dark">
            <a class="btn btn-primary btn-md" href="<?= Url::to(['answer', 'id' => $model->id]) ?>">
                <span>Пройти тест</span>
            </a>
        </div>
    </div>
    <aside class="main-aside">
    </aside>
</div>
