<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;

/** @var $this \yii\web\View */
/** @var $model \app\modules\ministry\models\Ministry */
/** @var \app\modules\news\controllers\frontend\NewsController $context */

$context = $this->context;

$this->title = $model->title;

$this->params['breadcrumbs'] = $breadcrumbs;
$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->text), 255),
], 'og:description');

?>
<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $model->title ?></h1>
        <p class="page-date text-light"><?= $model->asDateCreated() ?></p>
        <div class="post-content text-dark">
            <?= $model->text ?>
        </div>
    </div>
</div>
