<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\testing\models\Testing */
/* @var $exportFiles array */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Testing'), 'url' => ['index']];
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
        </p>
    </div>

    <div class="card-content">

        <?= DetailView::widget([
            'options' => ['class' => 'table'],
            'model' => $model,
            'attributes' => [
                'id',
                'title',
                'description:html',
                'timer',
                [
                    'attribute' => 'hidden',
                    'value' => $model->getHidden(),
                ],
                'createdAt',
                'updatedAt',
            ],
        ]) ?>
        <?php /*
        <p>
            <a class="btn btn-success btn-fill btn-wd" href="<?= Url::to(['/testing/result/export-xls', 'id' => $model->id]) ?>">
                <i class="glyphicon glyphicon-save-file"></i>
                Экпортировать ответы в XLSX
            </a>
        </p>
 */ ?>
    </div>
    <?php if (false && $exportFiles): ?>
        <div class="card-header">
            <h4 class="card-title">Готовые отчеты</h4>
        </div>

        <div class="card-content">
            <table class="table">
                <?php foreach ($exportFiles as $file): ?>
                    <tr>
                        <td width="90%">
                            <a href="<?= Url::to([
                                '/testing/result/download',
                                'file' => pathinfo($file, PATHINFO_BASENAME),
                            ]); ?>">
                                <?= pathinfo($file, PATHINFO_BASENAME); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to([
                                '/testing/result/delete-file',
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
