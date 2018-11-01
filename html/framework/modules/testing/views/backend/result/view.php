<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\testing\models\TestingResult */
/* @var $test app\modules\testing\models\Testing */
/* @var $questions [] app\modules\testing\models\TestingQuestion */
/* @var $answers_right int */
/* @var $answers_count int */

$this->title = $test->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Testing results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>Время выполнения: <?= Yii::$app->formatter->asTime($model->time) ?></p>
        <p>Правильных ответов: <?= $answers_right; ?> из <?= $answers_count ?></p>
    </div>

    <div class="card-content">

        <?php if ($questions): ?>
            <table id="w0" class="table detail-view">
                <tr>
                    <th>
                        Вопрос
                    </th>
                    <th>
                        Ответ
                    </th>
                    <th>
                        Правильный
                    </th>
                </tr>
                <?php foreach ($questions as $question): ?>
                    <tr>
                        <th><?= $question['question'] ?></th>
                        <td>
                            <?= $question['answer']; ?>
                        </td>
                        <td>
                            <?= $question['right'] ? 'Да' : 'Нет'; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <?= Html::a(Yii::t('system', 'Back'), Url::to(['index']), ['class' => 'btn btn-default']); ?>
            </div>
        </div>
    </div>

</div>