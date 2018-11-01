<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 20:06
 */
use yii\helpers\ArrayHelper;

/** @var $this \yii\web\View */
/** @var $resultAnswers array */
/** @var $resultAnswerTexts \app\modules\questionnaire\models\ResultAnswerText[] */
/** @var $context \app\modules\questionnaire\controllers\backend\ResultController */

$context = $this->context;
?>

<table class="table">
    <tr>
        <td>
            Вопрос
        </td>
        <td>
            Ответ
        </td>
    </tr>
    <?php foreach ($resultAnswers as $id => $resultAnswer): ?>
        <?php
        $answers = ArrayHelper::getColumn($resultAnswer, 'answer', 'title')
        ?>
        <tr>
            <td>
                <?= $context->getQuestionById($id)->title; ?>
            </td>
            <td>
                <?= implode(',', ArrayHelper::getColumn($answers, 'title')) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    <?php foreach ($resultAnswerTexts as $resultAnswerText): ?>
        <tr>
            <td>
                <?= ArrayHelper::getValue($resultAnswerText->question, ['title']) ?>
            </td>
            <td>
                <?= $resultAnswerText->text ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
