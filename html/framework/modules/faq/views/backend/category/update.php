<?php

use app\modules\faq\models\Faq;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\faq\models\FaqCategory */
/* @var $faqDataProvider \yii\data\ActiveDataProvider */
/* @var $faqSearchModel \app\modules\faq\models\FaqSearch */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('system', 'Update');
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?php $form = ActiveForm::begin(); ?>

        <?= $this->render('_form', ['form' => $form, 'model' => $model]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('system', 'Update'),
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="card-content">

        <h1>Вопросы категории</h1>

        <?= \yii\grid\GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $faqDataProvider,
            'filterModel' => $faqSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'question',
                'answer',
                [
                    'attribute' => 'hidden',
                    'value' => function (Faq $model) {
                        return $model->getHidden();
                    },
                    'filter' => $model::getHiddenList(),
                ],
                // 'createdAt',
                // 'updatedAt',

                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update}  {delete}',
                    'urlCreator' => function (
                        string $action,
                        Faq $model
                    ) {
                        switch ($action) {
                            case 'update':
                                return Url::to(['/faq/question/update', 'id' => $model->id]);
                                break;
                            case 'delete':
                                return Url::to(['/faq/question/delete', 'id' => $model->id]);
                                break;
                        }

                        return false;
                    },
                ],
            ],
        ]); ?>

        <div class="form-group">
            <?=
            Html::a(
                Yii::t('system', 'Create'),
                ['/faq/question/create', 'id' => $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </div>

    </div>
</div>
