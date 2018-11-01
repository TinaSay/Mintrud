<?php

use app\modules\reception\services\SendAppealService;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model app\modules\reception\models\Appeal */
/* @var $appealFile string|null */
/* @var $debug int */

$this->title = Yii::t('system', 'Update') . ' : ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Appeals'), 'url' => ['index']];
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
            <?= Html::a(Yii::t('system', 'Back'),
                ['index'],
                ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <?php if ($model->files): ?>
        <div class="card-header">
            <h4 class="card-title">Файлы прикрепленные к обращению</h4>
        </div>
        <div class="card-content">
            <ul>
                <?php foreach ($model->files as $file): ?>
                    <li>
                        <?php if ($debug >= SendAppealService::DEBUG_FILES): ?>
                            <a href="<?= $file->getDownloadUrl('src'); ?>">
                                <strong><?= $file->name; ?></strong> (<?= Yii::$app->formatter->asShortSize($file->getSize()); ?>)
                            </a>
                        <?php else: ?>
                            <strong><?= $file->name; ?></strong> (<?= Yii::$app->formatter->asShortSize($file->getSize()); ?>)
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
    <?php if ($appealFile && $debug >= SendAppealService::DEBUG_FILES): ?>
        <div class="card-header">
            <h4 class="card-title">Запрос в СЭД</h4>
        </div>
        <div class="card-content">
            <a href="<?= Url::to(['download-appeal', 'file' => $appealFile]); ?>">Скачать</a>
        </div>

    <?php endif; ?>
</div>
