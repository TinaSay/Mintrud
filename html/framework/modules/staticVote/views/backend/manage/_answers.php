<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 30.06.17
 * Time: 17:40
 */

/* @var $questions \app\modules\staticVote\models\StaticVoteQuestion[] */

/* @var $answers [] */

use app\modules\staticVote\models\StaticVoteAnswers;

?>
<table class="table">
    <thead>
    <tr>
        <th>Вопрос</th>
        <th>Ответ</th>
    </tr>
    </thead>
    <tbody>
    <? foreach ($answers as $question_id => $answer_id) : ?>
        <tr>
            <td class="col-md-8">
                <?= $questions[$question_id]['question']; ?>
            </td>
            <td class="col-md-4"><?= StaticVoteAnswers::getAnswerValue($questions[$question_id], $answer_id) ?> </td>
        </tr>
    <? endforeach; ?>
    </tbody>
</table>
