<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 20.10.17
 * Time: 12:17
 */

/** @var $this \yii\web\View */
/** @var $context \app\modules\questionnaire\widgets\CartWidget */
/** @var $model \app\modules\questionnaire\models\Questionnaire */

$context = $this->context;
?>

<div class="row">
    <h3>ПРОГОЛОСОВАЛО <?= $context->countResult(); ?> ЧЕЛОВЕК</h3>
</div>
<?php foreach ($model->questions as $question): ?>
    <?php if ($question->isCheckbox() || $question->isRadio() || $question->isSelect() || $question->isSelectMultiple()) : ?>
        <div class="panel panel-default">
            <div class="panel-heading"><?= $question->title ?></div>
            <div class="panel-body">
                <ul class="list-group">
                    <?php foreach ($question->answers as $answer): ?>
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-md-12">
                                    <?= $answer->title ?>
                                    <?= round($context->getPercent($question->id, $answer->id)); ?>%
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach; ?>





