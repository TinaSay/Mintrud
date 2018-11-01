<?php
/**
 * Created by PhpStorm.
 * User: petr
 * Date: 04.11.2016
 * Time: 11:57
 */

use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\questionnaire\models\Questionnaire */
/* @var $returnUrl string */


$this->title = $model->title;
?>
<div class="clearfix">
    <div class="main pd-bottom-80">
        <h1 class="page-title text-black pd-bottom-40"><?= $model->title ?></h1>

        <div class="post-content text-dark pd-bottom-40">
            <p>Вы уже проходили этот тест.</p>
        </div>
        <div class="text-dark">
            <a class="btn btn-primary btn-md" href="<?= Url::to(['index']) ?>">
                <span>Назад</span>
            </a>
        </div>
    </div>
    <aside class="main-aside">
    </aside>
</div>