<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 18:58
 */

declare(strict_types=1);

namespace app\modules\questionnaire\helps;


use app\modules\questionnaire\form\Checkbox;
use app\modules\questionnaire\form\Radio;
use app\modules\questionnaire\form\Select;
use app\modules\questionnaire\form\SelectMultiple;
use app\modules\questionnaire\form\Text;
use app\modules\questionnaire\form\Textarea;
use app\modules\questionnaire\models\Answer;
use app\modules\questionnaire\models\Question;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html as HtmlBase;

/**
 * Class Html
 * @package app\modules\questionnaire\helps
 */
class Html
{
    /**
     * @param string $action
     * @param string $method
     * @param array $options
     * @return string
     */
    public static function beginForm($action = '', string $method = 'post', array $options = []): string
    {
        return HtmlBase::beginForm($action, $method, $options);
    }

    /**
     * @param Answer $answer
     * @param array $options
     * @return string
     */
    public static function checkbox(Answer $answer, array $options = []): string
    {
        $default = ['value' => $answer->id];
        $options = ArrayHelper::merge($default, $options);
        $options['data']['id'] = $answer->id;
        return HtmlBase::checkbox(HtmlBase::getInputName((new Checkbox()), 'checkbox') . '[' . $answer->question_id . '][]', false, $options);
    }

    /**
     * @param Answer $answer
     * @param array $options
     * @return string
     */
    public static function radio(Answer $answer, array $options = []): string
    {
        $default = ['value' => $answer->id, 'required' => true];
        $options = ArrayHelper::merge($default, $options);
        $options['data']['id'] = $answer->id;
        return HtmlBase::radio(HtmlBase::getInputName((new Radio()), 'radio') . '[' . $answer->question_id . ']', false, $options);
    }

    /**
     * @param Question $question
     * @param array $options
     * @param Model|null $model
     * @return string
     */
    public static function dropDownList(Question $question, array $options = [], Model $model = null): string
    {
        $default = ['required' => true];
        $items = ArrayHelper::map($question->answersOrderByPosition, 'id', 'title');
        if (is_null($model)) {
            $model = new Select();
        }
        return HtmlBase::dropDownList(
            HtmlBase::getInputName($model, 'select') . '[' . $question->id . ']',
            null,
            $items,
            ArrayHelper::merge($default, $options)
        );
    }

    /**
     * @param Question $question
     * @param array $options
     * @return string
     */
    public static function dropDownListMultiple(Question $question, array $options = []): string
    {
        $default = ['multiple' => true];
        $options = ArrayHelper::merge($default, $options);
        return static::dropDownList($question, $options, new SelectMultiple());
    }

    /**
     * @param Question $question
     * @param array $options
     * @return string
     */
    public static function textInput(Question $question, array $options = []): string
    {
        $default = ['required' => true];
        $options = ArrayHelper::merge($default, $options);
        return HtmlBase::textInput(HtmlBase::getInputName(new Text(), 'text') . '[' . $question->id . ']', null, $options);
    }

    /**
     * @param Question $question
     * @param array $options
     * @return string
     */
    public static function textarea(Question $question, array $options = []): string
    {
        $default = [
            'required' => true
        ];
        $options = ArrayHelper::merge($default, $options);
        return HtmlBase::textarea(HtmlBase::getInputName(new Textarea(), 'textarea') . '[' . $question->id . ']', null, $options);
    }
}