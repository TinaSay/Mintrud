<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 14:55
 */

use app\modules\questionnaire\widgets\CartWidget;
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $model \app\modules\questionnaire\models\Questionnaire */

$this->title = $model->title;

$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $model->title ?></h1>
        <div class="post-content text-dark">
            <?= $model->description ?>
        </div>
        Ваш ответ успешно отправлен, спасибо за Ваше мнение!
        <?php if ($model->isBarCart()): ?>
            <?= CartWidget::widget(['model' => $model]) ?>
        <?php endif; ?>
    </div>
    <aside class="main-aside">
        <div class="border-block block-arrow">
            <p class="text-light">Категория:</p>
            <p class="pd-bottom-15"><?= ArrayHelper::getValue($model->directory, 'title'); ?></p>
        </div>
    </aside>
</div>


