<?php

use app\modules\testing\models\TestingQuestion;
use app\modules\testing\models\TestingQuestionCategory;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\testing\models\Testing */
/* @var $questions app\modules\testing\models\TestingQuestion[] */
/* @var $questionCategoryDataProvider \yii\data\ActiveDataProvider */
/* @var $questionDataProvider \yii\data\ActiveDataProvider */
/* @var $questionSearchModel \app\modules\testing\models\search\TestingQuestionSearch */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Testing'), 'url' => ['index']];
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

        <h1>Категории вопросов теста</h1>

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $questionCategoryDataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                'limit',
                [
                    'attribute' => 'hidden',
                    'value' => function (TestingQuestionCategory $model) {
                        return $model->getHidden();
                    },
                ],
                // 'createdAt',
                // 'updatedAt',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {delete}',
                    'urlCreator' => function ($action, $model) {
                        return Url::to(['/testing/category/' . $action, 'id' => $model->id]);
                    },
                ],
            ],
        ]); ?>

        <div class="form-group">
            <?=
            Html::a(
                Yii::t('system', 'Create'),
                ['/testing/category/create', 'id' => $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </div>

    </div>

    <div class="card-content">

        <h1>Вопросы теста</h1>

        <?= GridView::widget([
            'tableOptions' => ['class' => 'table'],
            'dataProvider' => $questionDataProvider,
            'filterModel' => $questionSearchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'title',
                [
                    'attribute' => 'categoryId',
                    'value' => function (TestingQuestion $model) {
                        return $model->category ? $model->category->title : null;
                    },
                    'filter' => TestingQuestionCategory::asDropDown($model->id),
                ],
                [
                    'attribute' => 'hidden',
                    'value' => function (TestingQuestion $model) {
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
                        TestingQuestion $model
                    ) {
                        switch ($action) {
                            case 'update':
                                return Url::to(['/testing/question/update', 'id' => $model->id]);
                                break;
                            case 'delete':
                                return Url::to(['/testing/question/delete', 'id' => $model->id]);
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
                ['/testing/question/create', 'id' => $model->id],
                ['class' => 'btn btn-success']
            ) ?>
        </div>

    </div>
</div>
