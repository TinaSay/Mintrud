<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 17:00
 */

use app\modules\staticVote\models\StaticVoteQuestion;
use yii\helpers\Html;

/* @var $this yii\web\View */
/** @var $model \app\modules\staticVote\models\StaticVoteQuestionnaire */
/** @var $questions \app\modules\staticVote\models\StaticVoteQuestion[] */

$baseName = 'Answer[questionnaire]';
$fieldsetStart = false;
?>
<?php if (Yii::$app->session->hasFlash('success')):
    $messages = Yii::$app->session->getFlash('success'); ?>
    <span class="alert alert-success">
        <?= is_array($messages) ? implode('<br>', $messages) : $messages; ?>
    </span>
<?php else: ?>
    <?php if (Yii::$app->session->hasFlash('danger')):
        $messages = Yii::$app->session->getFlash('danger');
        ?>
        <?php foreach ($messages as $message): ?>
        <span class="alert alert-danger">
        <?= implode('<br>', $message); ?>
    </span>
    <?php endforeach; ?>
    <?php endif; ?>
    <div class="vote_block">
    <h3>Проголосовало <?= $model['answers'] ?> чел.</h3>
    <?= Html::beginForm(['answer', 'id' => $model['id']], 'post', ['id' => 'form-questionnaire-answer']); ?>

    <?php foreach ($questions as $question):
        if ($question['input_type'] === StaticVoteQuestion::INPUT_TYPE_NONE):
            $fieldsetStart = (string)$question['id'];
            ?>
            <div class="panel">
            <div class="panel-header">
                <h2 class="title"><?= $question->question; ?></h2>
                <?php if (!empty($question['hint'])): ?>
                    <span class="shortstory"><?= $question['hint']; ?></span>
                <?php endif; ?>
            </div>
            <div class="panel-body">
        <?php else:
            $disabled = ($question->show_on_answer_check && (empty($fieldsetStart) || $question->show_on_answer_check != $fieldsetStart)) ? true : false;
            ?>
            <?php if ($fieldsetStart && empty($question->show_on_answer_check)):
            $fieldsetStart = false;
            ?>
            </div><!-- ./panel-body -->
            </div><!-- ./panel -->
        <?php endif; ?>
            <div class="desc-item <?= $disabled ? 'hidden' : ''; ?>" id="question_<?= $question->id; ?>"
                 data-show="<?= $question->show_on_answer_check; ?>|"
                 data-parent="<?= $question->getParentQuestionId(); ?>"
                 data-min="<?= $question->min_answers; ?>"
                 data-max="<?= $question->max_answers; ?>">
                <h2 class="title"><?= $question->question; ?></h2>
                <?php

                switch ($question['input_type']) {
                    case StaticVoteQuestion::INPUT_TYPE_TEXT:
                    case StaticVoteQuestion::INPUT_TYPE_NUMBER:
                        $options = [
                            'class' => 'form-control',
                            'disabled' => $disabled,
                        ];
                        if (isset($question['min_answers'])) {
                            $options['min'] = $question['min_answers'];
                        }
                        ?>
                        <?= Html::input($question['input_type'], $baseName . '[' . $question['id'] . ']',
                        ($question['input_type'] === StaticVoteQuestion::INPUT_TYPE_NUMBER ? $question['min_answers'] : ''),
                        $options
                    ); ?>
                        <?php break;
                    case StaticVoteQuestion::INPUT_TYPE_TEXTAREA:
                        ?>
                        <?= Html::textarea($baseName . '[' . $question['id'] . ']', '', [
                        'class' => 'form-control',
                        'disabled' => $disabled,
                    ]); ?>
                        <?php break;
                    case StaticVoteQuestion::INPUT_TYPE_RADIO:
                        ?>
                        <ol>
                            <?php foreach ($question->answers as $id => $answer): ?>
                                <li>
                                    <?= Html::radio($baseName . '[' . $question['id'] . ']', false, [
                                        'class' => 'form-control radio',
                                        'id' => 'answer_' . $question['id'] . '_' . $id,
                                        'disabled' => $disabled,
                                        'value' => $id,
                                        'data-id' => $question['id'] . '_' . $id,
                                    ]); ?>
                                    <?= Html::tag('label', $answer, [
                                        'class' => 'text',
                                        'for' => 'answer_' . $question['id'] . '_' . $id,
                                    ]); ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php break;
                    case StaticVoteQuestion::INPUT_TYPE_SELECT:
                        ?>
                        <?= Html::dropDownList($baseName . '[' . $question['id'] . ']', '', $question->answers,
                        [
                            'class' => 'form-control',
                            'disabled' => $disabled,
                            'prompt' => 'Выберите',
                        ]
                    ); ?>
                        <?php break;
                    case StaticVoteQuestion::INPUT_TYPE_CHECKBOX:
                        ?>
                        <ol>
                            <?php foreach ($question->answers as $id => $answer): ?>
                                <li>
                                    <?= Html::checkbox($baseName . '[' . $question['id'] . '][]', false, [
                                        'class' => 'form-control radio',
                                        'id' => 'answer_' . $question['id'] . '_' . $id,
                                        'disabled' => $disabled,
                                        'value' => $id,
                                        'data-id' => $question['id'] . '_' . $id,
                                    ]); ?>
                                    <?= Html::tag('label', $answer, [
                                        'class' => 'text',
                                        'for' => 'answer_' . $question['id'] . '_' . $id,
                                    ]); ?>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                        <?php break; ?>
                        <?php
                } ?>
                <?php if ($question['hint']): ?>
                    <span class="hint"><?= $question['hint']; ?></span>
                <?php endif; ?>
            </div>
        <?php endif;
        ?>

    <?php endforeach; ?>
    <?php if ($fieldsetStart):
        $fieldsetStart = false;
        ?>
        </div><!-- ./panel-body -->
        </div><!-- ./panel -->
    <?php endif; ?>

    <?= Html::submitButton('Отправить', ['class' => 'b-send']); ?>
    <br>
    <h3 class="c-red invalid_form hidden" id="vote_status">
        Перед отправкой Вашего ответа, заполните пожалуйста все поля.
    </h3>
    <?= Html::endForm(); ?>
    </div> <!-- ./vote_block -->
<?php endif; ?>