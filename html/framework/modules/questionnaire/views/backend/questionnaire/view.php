<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\questionnaire\models\Questionnaire */
/* @var $exportFiles [] */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Questionnaire'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('system', 'Update'), ['update', 'id' => $model->id], [
                'class' => 'btn btn-primary',
            ]) ?>
            <?= Html::a(Yii::t('system', 'Delete'), ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => Yii::t('system', 'Are you sure you want to delete this item?'),
                    'method' => 'post',
                ],
            ]) ?>
            <?= Html::a('Вопросы', ['/questionnaire/question', 'id' => $model->id], [
                'class' => 'btn btn-info',
            ]) ?>
        </p>
    </div>

    <div class="card-content">

        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                [
                    'attribute' => 'directory_id',
                    'value' => ArrayHelper::getValue($model->directory, 'title'),
                ],
                'title',
                'description:html',
                [
                    'label' => 'Url',
                    'value' => Url::to('/' . ArrayHelper::getValue($model, ['directory', 'url']) . '/q-' . $model->id,
                        true),
                    'format' => 'url',
                ],
                [
                    'label' => 'Url алиас',
                    'value' => !empty($model->name) ? Url::to('/nsok/' . $model->name, true) : null,
                    'format' => 'url',
                ],
                [
                    'attribute' => 'show_result',
                    'value' => $model->getShowResult(),
                ],
                [
                    'attribute' => 'restriction_by_ip',
                    'value' => $model->getRestrictionByIp(),
                ],
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHidden(),
                ],
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
        <p>
            <!-- <a class="btn btn-success btn-fill btn-wd" href="<?= Url::to(['export', 'id' => $model->id]) ?>">
                <i class="glyphicon glyphicon-export"></i>
                Экпортировать ответы
            </a>
            -->
            <a class="btn btn-success btn-fill btn-wd" href="<?= Url::to(['/questionnaire/result/export-xls', 'id' => $model->id]) ?>">
                <i class="glyphicon glyphicon-save-file"></i>
                Экпортировать ответы в XLSX
            </a>
        </p>
    </div>
    <?php if ($exportFiles): ?>
        <div class="card-header">
            <h4 class="card-title">Готовые отчеты</h4>
        </div>

        <div class="card-content">
            <table class="table">
                <?php foreach ($exportFiles as $file): ?>
                    <tr>
                        <td width="90%">
                            <a href="<?= Url::to(['/questionnaire/result/download', 'file' => pathinfo($file, PATHINFO_BASENAME)]); ?>">
                                <?= pathinfo($file, PATHINFO_BASENAME); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to([
                                '/questionnaire/result/delete-file',
                                'id' => $model->id,
                                'file' => pathinfo($file, PATHINFO_BASENAME),
                            ]); ?>" data-confirm="Удалить файл?" class="btn btn-danger">
                                <?= Yii::t('yii', 'Delete'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>
</div>
