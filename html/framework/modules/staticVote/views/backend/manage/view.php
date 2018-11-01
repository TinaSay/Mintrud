<?php

use app\modules\staticVote\models\StaticVoteAnswers;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\modules\staticVote\models\StaticVoteQuestionnaire */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $exportFiles [] */

$this->title = 'Ответы на опрос: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'System votes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">
        <?= \app\widgets\alert\AlertWidget::widget(); ?>

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                [
                    'attribute' => 'ip',
                    'value' => function (StaticVoteAnswers $model) {
                        return $model->ip ? long2ip($model->ip) : '';
                    },
                ],
                'created_at:datetime',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            $url = Url::to(['answers', 'id' => $model->id]);

                            return Html::a('<span class="glyphicon glyphicon-comment"></span>', $url, [
                                'title' => 'Просмотреть ответы',
                            ]);
                        },
                    ],
                ],
            ],
        ]); ?>
        <p>
            <!-- <a class="btn btn-success btn-fill btn-wd" href="<?= Url::to(['export', 'id' => $model->id]) ?>">
                <i class="glyphicon glyphicon-export"></i>
                Экпортировать ответы
            </a>
            -->
            <a class="btn btn-success btn-fill btn-wd" href="<?= Url::to(['export-xls', 'id' => $model->id]) ?>">
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
                            <a href="<?= Url::to(['download', 'file' => pathinfo($file, PATHINFO_BASENAME)]); ?>">
                                <?= pathinfo($file, PATHINFO_BASENAME); ?>
                            </a>
                        </td>
                        <td>
                            <a href="<?= Url::to([
                                'delete-file',
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
