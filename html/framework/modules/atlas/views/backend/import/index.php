<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $rateList array */
/* @var $yearList array */
/* @var $list \app\modules\atlas\models\AtlasStat[] */
/* @var $searchModel \app\modules\atlas\models\search\AtlasStatSearch */
/* @var $import string */

$this->title = 'Импорт статистики';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="card">
    <div class="group-index">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <?= $this->render('_search', [
            'rateList' => $rateList,
            'yearList' => $yearList,
            'model' => $searchModel,
        ]); ?>
        <?php if ($searchModel->directory_rate_id && $searchModel->year): ?>
            <div class="card-content">
                <div class="row">
                    <?= \app\widgets\alert\AlertWidget::widget();?>
                    <?= Html::beginForm(); ?>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-header">
                                <h2>Данные для импорта</h2>
                            </div>
                            <div class="panel-body">
                                <?= Html::textarea('import', $import,
                                    [
                                        'class' => 'form-control',
                                        'style' => 'min-height:600px;height:100%;',
                                    ]
                                ); ?>
                            </div>
                            <div class="panel-footer">
                                <?= Html::submitButton('Импортировать', ['class' => 'btn btn-primary']); ?>
                            </div>
                        </div>
                    </div>

                    <?= Html::endForm(); ?>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-header">
                                <h2>Текущие значения</h2>
                            </div>
                            <div class="panel-body">
                                <?php if ($list): ?>
                                    <ul class="list">
                                        <?php foreach ($list as $model) : ?>
                                            <li>
                                        <span class="pull-left">
                                            <?= $model->directorySubject->title; ?>
                                        </span>
                                                <span class="pull-right text text-bold">
                                            <?= $model->value; ?>
                                        </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Нет показателей</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- ./card-content -->
        <?php else: ?>
            <div class="card-content">
                <p>Для начала импорта выберите Тип показателя и Год</p>
            </div>
        <?php endif; ?>
    </div>
</div>