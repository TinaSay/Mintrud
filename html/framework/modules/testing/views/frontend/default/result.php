<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $model \app\modules\testing\models\Testing */
/** @var $result \app\modules\testing\models\TestingResult */
/** @var $answerCount int */
/** @var $answerRightCount int */
/** @var $percentRight int */

$this->title = 'Результат выполнения теста';

$this->params['breadcrumbs'] = [
    ['label' => 'Тестирование', 'url' => ['/testing/default/index']],
    ['label' => $model->title, 'url' => ['/testing/default/view', 'id' => $model->id]],
    ['label' => $this->title],

];

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->description), 255),
], 'og:description');
?>
<div class="clearfix">
    <div class="main pd-bottom-80">
        <h1 class="page-title text-black pd-bottom-10"><?= $this->title; ?></h1>
        <h4 class="text-black"><?= $model->title ?></h4>
        <div class="post-content text-dark pd-bottom-40 pd-top-50">
            <?php if ($model->timer): ?>
                <p>Ограничение по времени: <?= $model->asTime(); ?>,
                    выполнен за <?= $result->asTime(); ?>
                </p>
            <?php endif; ?>
            <p>
                Отвечено верно на <?= $answerRightCount; ?> из <?= $answerCount ?> вопросов,
                что составляет <?= $percentRight; ?>% от общего
                числа заданных вопросов в тесте
            </p>
            <p>
                Набрано <?= $answerRightCount; ?> баллов из <?= $answerCount ?>,
                что составляет <?= $percentRight; ?>% от максимального числа
                баллов за тест
            </p>
        </div>
        <div class="text-dark">
            <a class="btn btn-primary btn-md" href="<?= Url::to(['index']) ?>">
                <span>Вернуться</span>
            </a>
        </div>
    </div>
    <aside class="main-aside">
    </aside>
</div>
