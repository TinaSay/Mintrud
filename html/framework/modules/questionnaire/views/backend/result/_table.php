<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 09.07.2017
 * Time: 15:45
 */
use yii\helpers\ArrayHelper;

/** @var $this \yii\base\View */
/** @var $resultAnswerTexts \app\modules\questionnaire\models\ResultAnswerText[] */
/** @var $resultAnswers \app\modules\questionnaire\models\ResultAnswer[] */
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
