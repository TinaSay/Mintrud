<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 15:57
 */


/** @var $this \yii\web\View */
/** @var $question \app\modules\questionnaire\models\Question */

/** @var $context \app\modules\questionnaire\widgets\Form */
$context = $this->context;
?>

<ul>
    <?php foreach ($question->answersOrderByPosition as $answer): ?>
        <li>
        	<label class="wrap-check">
            <?= $context->checkbox($answer) ?>
            </label>
        </li>
    <?php endforeach; ?>
</ul>
