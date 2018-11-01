<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.07.2017
 * Time: 17:14
 */
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $model \app\modules\questionnaire\models\Question */
/** @var $dropDown array */

$this->title = Yii::t('system', 'Create');
$this->params['breadcrumbs'][] = ['label' => 'Вопросы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
        'dropDown' => $dropDown,
    ]) ?>

</div>