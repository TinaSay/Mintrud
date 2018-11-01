<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = <?= $generator->generateString('Create') ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::camel2words(StringHelper::basename($generator->modelClass))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">

    <div class="card-header">
        <h4 class="card-title"><?= "<?= " ?>Html::encode($this->title) ?></h4>
    </div>

    <div class="card-content">

        <?= "<?php " ?>$form = ActiveForm::begin(); ?>

        <?= "<?= " ?>$this->render('_form', ['form' => $form, 'model' => $model]) ?>

        <div class="form-group">
            <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('Create') ?>,
            ['class' => 'btn btn-success']) ?>
        </div>

        <?= "<?php " ?>ActiveForm::end(); ?>

    </div>

</div>
