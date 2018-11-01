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
    <?= Html::beginForm(['answer', 'id' => $model['id']], 'post', [
        'class' => 'form form-defoult-validate',
        'id' => 'form-questionnaire-answer',
    ]); ?>

    <?php foreach ($questions as $question):
        if ($question['input_type'] === StaticVoteQuestion::INPUT_TYPE_NONE):
            $fieldsetStart = (string)$question['id'];
            ?>
            <h4 class="title form-titlle"><?= $question->question; ?></h4>
            <?php if ($question->hint): ?>
            <div class="text-black parent-question post-content">
                <span class="hint"><?= $question->hint; ?></span>
            </div>
        <?php endif; ?>
        <?php else:
            $disabled = ($question->show_on_answer_check && (empty($fieldsetStart) || $question->show_on_answer_check != $fieldsetStart)) ? true : false;
            ?>
            <?php if ($fieldsetStart && empty($question->show_on_answer_check)):
            $fieldsetStart = false;
            ?>
        <?php endif; ?>
            <?php
            switch ($question['input_type']) {
                case StaticVoteQuestion::INPUT_TYPE_TEXT:
                case StaticVoteQuestion::INPUT_TYPE_NUMBER:
                    $options = [
                        'class' => 'form-control',
                        'disabled' => $disabled,
                        'required' => !$disabled,
                    ];
                    if (isset($question['min_answers'])) {
                        $options['min'] = $question['min_answers'];
                    }
                    ?>
                    <div class="form-group parent-question form-group--title-top <?= $disabled ? 'sub-question hidden' : ''; ?>"
                         id="question_<?= $question->id; ?>"
                         data-show="<?= $question->show_on_answer_check; ?>|"
                         data-parent="<?= $question->getParentQuestionId(); ?>"
                         data-min="<?= $question->min_answers; ?>"
                         data-max="<?= $question->max_answers; ?>">
                        <?= Html::tag('label', $question->question, ['class' => 'placeholder']); ?>
                        <?= Html::input($question['input_type'], $baseName . '[' . $question['id'] . ']',
                            //($question['input_type'] === StaticVoteQuestion::INPUT_TYPE_NUMBER ? $question['min_answers'] : ''),
                            '',
                            $options
                        ); ?>
                        <?php if ($question->hint): ?>
                            <div class="text-black post-content pd-top-10">
                                <span class="hint"><?= $question->hint; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php break;
                case StaticVoteQuestion::INPUT_TYPE_TEXTAREA:
                    ?>
                    <div class="form-group parent-question form-group--title-top <?= $disabled ? 'sub-question hidden' : ''; ?>"
                         id="question_<?= $question->id; ?>"
                         data-show="<?= $question->show_on_answer_check; ?>|"
                         data-parent="<?= $question->getParentQuestionId(); ?>"
                         data-min="<?= $question->min_answers; ?>"
                         data-max="<?= $question->max_answers; ?>">
                        <?= Html::tag('label', $question->question, ['class' => 'placeholder']); ?>
                        <?= Html::textarea($baseName . '[' . $question['id'] . ']', '', [
                            'class' => 'form-control',
                            'disabled' => $disabled,
                            'required' => !$disabled,
                        ]); ?>
                        <?php if ($question->hint): ?>
                            <div class="text-black post-content pd-top-10">
                                <span class="hint"><?= $question->hint; ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php break;
                case StaticVoteQuestion::INPUT_TYPE_RADIO:
                    ?>
                    <div class="form-group parent-question <?= $disabled ? 'sub-question hidden' : ''; ?>"
                         id="question_<?= $question->id; ?>"
                         data-show="<?= $question->show_on_answer_check; ?>|"
                         data-parent="<?= $question->getParentQuestionId(); ?>"
                         data-min="<?= $question->min_answers; ?>"
                         data-max="<?= $question->max_answers; ?>">
                        <?= Html::tag('label', $question->question); ?>
                        <?php if ($question->hint): ?>
                            <div class="text-black post-content pd-bottom-20">
                                <span class="hint"><?= $question->hint; ?></span>
                            </div>
                        <?php endif; ?>
                        <ul>
                            <?php foreach ($question->answers as $id => $answer): ?>
                                <li>
                                    <div class="wrap-check">
                                        <?= Html::radio($baseName . '[' . $question['id'] . ']', false, [
                                            'class' => 'form-control radio',
                                            'id' => 'answer_' . $question['id'] . '_' . $id,
                                            'disabled' => $disabled,
                                            'required' => !$disabled,
                                            'value' => $id,
                                            'data-id' => $question['id'] . '_' . $id,
                                        ]); ?>
                                        <?= Html::tag('label', $answer, [
                                            'for' => 'answer_' . $question['id'] . '_' . $id,
                                        ]); ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php break;
                case StaticVoteQuestion::INPUT_TYPE_SELECT:
                    ?>
                    <div class="form-group parent-question <?= $disabled ? 'sub-question hidden' : ''; ?>"
                         id="question_<?= $question->id; ?>"
                         data-show="<?= $question->show_on_answer_check; ?>|"
                         data-parent="<?= $question->getParentQuestionId(); ?>"
                         data-min="<?= $question->min_answers; ?>"
                         data-max="<?= $question->max_answers; ?>">
                        <?php if ($question->hint): ?>
                            <div class="text-black post-content pd-bottom-20">
                                <span class="hint"><?= $question->hint; ?></span>
                            </div>
                        <?php endif; ?>
                        <?= Html::dropDownList($baseName . '[' . $question['id'] . ']', '', $question->answers,
                            [
                                'class' => 'selectpicker',
                                'disabled' => $disabled,
                                'required' => !$disabled,
                                'title' => 'Выберите',
                            ]
                        ); ?>
                    </div>
                    <?php break;
                case StaticVoteQuestion::INPUT_TYPE_CHECKBOX:
                    ?>
                    <div class="form-group parent-question <?= $disabled ? 'sub-question hidden' : ''; ?>"
                         id="question_<?= $question->id; ?>"
                         data-show="<?= $question->show_on_answer_check; ?>|"
                         data-parent="<?= $question->getParentQuestionId(); ?>"
                         data-min="<?= $question->min_answers; ?>"
                         data-max="<?= $question->max_answers; ?>">
                        <?= Html::tag('label', $question->question); ?>
                        <?php if ($question->hint): ?>
                            <div class="text-black post-content pd-bottom-20">
                                <span class="hint"><i><?= $question->hint; ?></i></span>
                            </div>
                        <?php endif; ?>
                        <ul>
                            <?php foreach ($question->answers as $id => $answer): ?>
                                <li>
                                    <div class="wrap-check">
                                        <?= Html::checkbox($baseName . '[' . $question['id'] . '][]', false, [
                                            'class' => 'form-control checkbox',
                                            'id' => 'answer_' . $question['id'] . '_' . $id,
                                            'disabled' => $disabled,
                                            'required' => !$disabled,
                                            'value' => $id,
                                            'data-id' => $question['id'] . '_' . $id,
                                        ]); ?>
                                        <?= Html::tag('label', $answer, [
                                            'for' => 'answer_' . $question['id'] . '_' . $id,
                                        ]); ?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php break; ?>
                    <?php
            } ?>
        <?php endif; ?>

    <?php endforeach; ?>
    <div class="two-btn pd-top-35">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary btn-lg two-btn__elem']); ?>
    </div>
    <?= Html::endForm(); ?>
<?php endif; ?>