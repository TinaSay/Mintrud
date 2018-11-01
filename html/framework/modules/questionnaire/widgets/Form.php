<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 09.07.2017
 * Time: 16:55
 */

declare(strict_types=1);

namespace app\modules\questionnaire\widgets;

use app\modules\questionnaire\helps\Html as HtmlQuestionnaire;
use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\Question;
use app\modules\questionnaire\models\Type;
use app\modules\questionnaire\widgets\assets\FormAsset;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Form
 *
 * @package app\modules\questionnaire\widgets
 */
class Form extends Widget
{
    /**
     * @var array|string the form action URL. This parameter will be processed by [[\yii\helpers\Url::to()]].
     * @see method for specifying the HTTP method for this form.
     */
    public $action = '';
    /**
     * @var string the form submission method. This should be either `post` or `get`. Defaults to `post`.
     *
     * When you set this to `get` you may see the url parameters repeated on each request.
     * This is because the default value of [[action]] is set to be the current request url and each submit
     * will add new parameters instead of replacing existing ones.
     * You may set [[action]] explicitly to avoid this:
     *
     * ```php
     * $form = ActiveForm::begin([
     *     'method' => 'get',
     *     'action' => ['controller/action'],
     * ]);
     * ```
     */
    public $method = 'post';
    /**
     * @var array the HTML attributes (name-value pairs) for the form tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];

    /**
     * Initializes the widget.
     * This renders the form open tag.
     */
    public function init()
    {
        $this->options['id'] = 'questionnaire-form';
        FormAsset::register($this->getView());
        ob_start();
        ob_implicit_flush((int)false);
    }

    /**
     * Runs the widget.
     * This registers the necessary JavaScript code and renders the form open and close tags.
     */
    public function run()
    {

        $content = ob_get_clean();
        echo HtmlQuestionnaire::beginForm($this->action, $this->method, $this->options);
        echo $content;

        echo Html::endForm();
    }


    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function checkboxes(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        $html .= Html::tag('label', $question->title);
        $html .= $this->render('form/checkbox', ['question' => $question, 'options' => $options]);
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Answer $answer
     * @param array $options
     *
     * @return string
     */
    public function checkbox(Answer $answer, array $options = []): string
    {
        return HtmlQuestionnaire::checkbox($answer, $options) . Html::tag('label', $answer->title);
    }

    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function radios(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        $html .= Html::tag('label', $question->title);
        $html .= $this->render('form/radio', ['question' => $question, 'options' => $options]);
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Answer $answer
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function radio(Answer $answer, Question $question, array $options = []): string
    {
        $html = '';
        if (!is_null($question->parent_question_id) && $question->parentAnswers) {
            $html .= HtmlQuestionnaire::radio(
                    $answer,
                    ArrayHelper::merge(['required' => false, 'disabled' => true, 'id' => 'answer-' . $answer->id],
                        $options)
                ) . Html::tag('label', $answer->title, ['for' => 'answer-' . $answer->id]);
        } else {
            $html .= HtmlQuestionnaire::radio($answer,
                    ArrayHelper::merge(['required' => true, 'id' => 'answer-' . $answer->id],
                        $options)) .
                Html::tag('label', $answer->title, ['for' => 'answer-' . $answer->id]);
        }

        return $html;
    }

    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function dropDownList(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        if (!is_null($question->parent_question_id) && $question->parentAnswers) {
            $html .= HtmlQuestionnaire::dropDownList(
                $question,
                ArrayHelper::merge(['required' => false, 'disabled' => true], $options)
            );
        } else {
            $html .= HtmlQuestionnaire::dropDownList($question, $options);
        }
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function dropDownListMultiple(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        $html .= Html::tag('label', $question->title, ['class' => 'placeholder']);
        if (!is_null($question->parent_question_id) && $question->parentAnswers) {
            $html .= HtmlQuestionnaire::dropDownListMultiple(
                $question,
                ArrayHelper::merge(['required' => false, 'disabled' => true], $options)
            );
        } else {
            $html .= HtmlQuestionnaire::dropDownListMultiple($question, $options);
        }
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function textInput(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        $html .= Html::tag('label', $question->title, ['class' => 'placeholder']);
        if (!is_null($question->parent_question_id) && $question->parentAnswers) {
            $html .= HtmlQuestionnaire::textInput(
                $question,
                ArrayHelper::merge(['required' => false, 'disabled' => true], $options)
            );
        } else {
            $html .= HtmlQuestionnaire::textInput($question, $options);
        }
        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Question $question
     * @param array $options
     *
     * @return string
     */
    public function textarea(Question $question, array $options = []): string
    {
        $html = '';
        $html .= $this->beginTag($question);
        $html .= Html::tag('label', $question->title, ['class' => 'placeholder']);
        if (!is_null($question->parent_question_id) && $question->parentAnswers) {
            $html .= HtmlQuestionnaire::textarea(
                $question,
                ArrayHelper::merge(['required' => false, 'disabled' => true], $options)
            );
        } else {
            $html .= HtmlQuestionnaire::textarea($question, $options);
        }

        $html .= Html::endTag('div');

        return $html;
    }

    /**
     * @param Question $question
     *
     * @return string
     */
    private function beginTag(Question $question): string
    {
        $html = '';
        $childrenIds = ArrayHelper::getColumn($question->childrenQuestions, 'id');
        $cssClass = 'form-group ';
        if ($question->type == Type::TYPE_ID_TEXT) {
            $cssClass .= 'form-group--title-top ';
        }
        if ($question->type == Type::TYPE_ID_TEXTAREA) {
            $cssClass .= 'form-group--title-top ';
        }
        if (!is_null($question->parent_question_id)) {
            $parentAnswerIds = ArrayHelper::getColumn($question->parentAnswers, 'id');
            $parentQuestionId = ArrayHelper::getValue($question->parentQuestion, 'id');
            if (!empty($parentAnswerIds)) {
                $cssClass .= 'hidden ';
            }
            $html .= Html::beginTag('div', [
                'class' => $cssClass .
                    'parent-question sub-question',
                'data' => [
                    'id' => $question->id,
                    'children_id' => $childrenIds,
                    'parent_answer_id' => $parentAnswerIds,
                    'parent_id' => $parentQuestionId,
                ],
            ]);
        } else {
            $html .= Html::beginTag('div', [
                'class' => $cssClass . 'parent-question',
                'data' => [
                    'id' => $question->id,
                    'children_id' => $childrenIds,
                ],
            ]);
        }

        return $html;
    }
}