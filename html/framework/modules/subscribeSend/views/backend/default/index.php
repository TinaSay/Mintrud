<?php
/* @var $this yii\web\View */

use yii\helpers\Html;
use app\modules\subscribeSend\models\SubscribeSend;
use app\modules\newsletter\models\Newsletter;
$this->title = 'Рассылка писем вручную';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="subscribesend-index">

        <div class="card-header">
            <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
        </div>

        <div class="card-content">
            <div class="container">
                <div class="row">
                    <div class="col-xs-6">
                        <div><h3>Рассылка "Новости"</h3></div>
                        <div>
                            <?= Html::a('Ежедневно', [
                                'default/send',
                                'flag' => Newsletter::TIME_DAILY,
                                'model' =>
                                    SubscribeSend::NEWS_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-primary',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                        <div>
                            <?= Html::a('Еженедельно', [
                                'default/send',
                                'flag' => Newsletter::TIME_WEEKLY,
                                'model' =>
                                    SubscribeSend::NEWS_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-success',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                        <div>
                            <?= Html::a('По мере добавления', [
                                'default/send',
                                'flag' => Newsletter::TIME_NOW,
                                'model' =>
                                    SubscribeSend::NEWS_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-danger',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div><h3>Рассылка "Мероприятия"</h3></div>
                        <div>
                            <?= Html::a('Ежедневно', [
                                'default/send',
                                'flag' => Newsletter::TIME_DAILY,
                                'model' =>
                                    SubscribeSend::EVENT_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-primary',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                        <div>
                            <?= Html::a('Еженедельно', [
                                'default/send',
                                'flag' => Newsletter::TIME_WEEKLY,
                                'model' =>
                                    SubscribeSend::EVENT_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-success',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                        <div>
                            <?= Html::a('По мере добавления', [
                                'default/send',
                                'flag' => Newsletter::TIME_NOW,
                                'model' =>
                                    SubscribeSend::EVENT_CLASS,

                            ],
                                [
                                    'class' => 'btn btn-danger',
                                    'style' => 'width:350px;'
                                ])
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>