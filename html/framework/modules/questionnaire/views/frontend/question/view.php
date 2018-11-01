<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 13:34
 */

use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\models\Type;
use app\modules\questionnaire\widgets\Form;
use yii\captcha\Captcha;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $questions \app\modules\questionnaire\models\Question[] */
/** @var $questionnaire \app\modules\questionnaire\models\Questionnaire */

$parent_question_id = 0;
$this->title = $questionnaire->title;

if ($questionnaire->name == 'survey_citizens') {
    $this->params['breadcrumbs'][] = ['label' => 'Независимая система оценки качества', 'url' => ['/nsok']];
}

$this->params['breadcrumbs'][] = ['label' => $this->title];

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => preg_replace("#([\r\n\t\s]+)#", ' ', $questionnaire->description),
], 'og:description');

$validators = (new Result())->getActiveValidators('captcha');

foreach ($validators as $validator) {
    if ($validator instanceof \yii\captcha\CaptchaValidator) {
        $js = $validator->clientValidateAttribute(new Result(), 'captcha', $this);
    }
}

$this->registerJs(<<<JS
    function validateCaptcha (value, messages) {
       $js 
    };
    function validate() {
        var value = $('input[name=captcha]').val();
        var messages = [];
        validateCaptcha(value, messages);
        if (!yii.validation.isEmpty(messages)) {
            $('.form-group-captcha').addClass('has-error');
            $('.form-group-captcha .help-block').text(messages[0]);
            return false;
        }  else {
            $('.form-group-captcha').removeClass('has-error');
            $('.form-group-captcha .help-block').text('');
            return true;
        }
    }
    $('.form.form-defoult-validate').submit(validate);
JS
)

?>

<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title ?></h1>
                <div class="post-content text-black pd-bottom-35">
                    <?= $questionnaire->description ?>
                </div>

                <div class="bg-gray bg-box text-black">
                    <div class="container-fluid">
                        <div class="row">
                            <?php $form = Form::begin([
                                'options' => [
                                    'class' => 'form form-defoult-validate',
                                ],
                            ]); ?>
                            <?php foreach ($questions as $question): ?>
                                <?php if ($question->childrenQuestions):
                                    $parent_question_id = $question->id;
                                    ?>
                                    <div class="question-panel">
                                    <div class="question-panel-heading">
                                        <h4 class="title form-titlle"><?= $question->title; ?></h4>
                                        <?php if ($question->hint): ?>
                                            <div class="text-black post-content pd-top-25 pd-bottom-30">
                                                <span class="hint"><?= $question->hint; ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="question-panel-body">
                                <?php endif; ?>
                                <?php if ($parent_question_id > 0 &&
                                    $question->id != $parent_question_id &&
                                    $question->parent_question_id != $parent_question_id):
                                    $parent_question_id = 0;
                                    ?>
                                    </div>
                                    </div>
                                <?php endif; ?>
                                <?php
                                switch ($question->type) {
                                    case Type::TYPE_ID_NONE:
                                        break;
                                    case Type::TYPE_ID_RADIO:
                                        echo $form->radios($question, ['class' => 'form-control']);
                                        break;
                                    case Type::TYPE_ID_CHECKBOX:
                                        echo $form->checkboxes($question, ['class' => 'form-control']);
                                        break;
                                    case Type::TYPE_ID_SELECT:
                                        echo $form->dropDownList($question,
                                            ['class' => 'selectpicker', 'title' => $question->title]);
                                        break;
                                    case Type::TYPE_ID_TEXT:
                                        echo $form->textInput($question, ['class' => 'form-control']);;
                                        break;
                                    case Type::TYPE_ID_TEXTAREA:
                                        echo $form->textarea($question, ['class' => 'form-control']);
                                        break;
                                    case Type::TYPE_ID_SELECT_MULTIPLE:
                                        echo $form->dropDownListMultiple($question,
                                            ['class' => 'selectpicker form-control']);
                                        break;
                                }
                                ?>
                            <?php endforeach; ?>
                            <?php if ($parent_question_id > 0): ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <?= Captcha::widget([
                            'captchaAction' => ['/questionnaire/question/captcha'],
                            'name' => Result::CAPTCHA_NAME,
                            'template' => '<div class="col-lg-6 captcha-image">{image}</div>' .
                                '<div class="col-lg-6">' .
                                '<div class="form-group form-group--placeholder-fix form-group-captcha">' .
                                '<label for="captcha" class="placeholder">Введите код с картинки </label>' .
                                '{input}' .
                                '<div class="help-block"></div>' .
                                '</div>' .
                                '</div>',
                        ]) ?>
                    </div>
                    <div class="two-btn pd-top-35">
                        <?= Html::submitButton('Отправить',
                            ['class' => 'btn btn-primary btn-lg two-btn__elem']); ?>
                    </div>
                    <?php $form::end() ?>
                </div>
            </div>
        </div>
    </div>
    <aside class="main-aside">
        <div class="border-block block-arrow">
            <p class="text-light">Категория:</p>
            <p class="pd-bottom-15"><?= ArrayHelper::getValue($questionnaire->directory, 'title'); ?></p>
        </div>
    </aside>
</section>