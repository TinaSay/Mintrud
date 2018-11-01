<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 15:43
 */

use app\components\helpers\StringHelper;
use app\modules\testing\assets\TestingAsset;
use app\modules\testing\models\TestingQuestion;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $model \app\modules\testing\models\Testing */
/** @var $answerModel \app\modules\testing\models\TestingQuestionAnswer */
/** @var $questions [] */

$this->title = $model->title;

$this->params['breadcrumbs'] = [
    ['label' => 'Тестирование', 'url' => ['/testing/default/index']],
    ['label' => $model->title, 'url' => ['/testing/default/view', 'id' => $model->id]],

];

TestingAsset::register($this);

$this->registerMetaTag([
    'property' => 'og:description',
    'content' => StringHelper::truncate(strip_tags($model->description), 255),
], 'og:description');
?>
    <div class="clearfix">
        <div class="main pd-bottom-80">
            <h1 class="page-title text-black pd-bottom-40"><?= $this->title; ?></h1>
            <div class="bg-gray bg-box text-black grey-interview-container">
            <div class="container-fluid">
                <?= Html::beginForm(['answer', 'id' => $model->id], 'post',
                    [
                        'class' => 'form',
                        'id' => 'testing_form',
                    ]); ?>
                <div class="post-content text-dark">
                    <div class="question-form__header clearfix">
                        <div class="col-sm-6">
                            <div class="question-form__step-info">Вопрос <span class="question-active-step">1</span> из
                                <?= count($questions); ?>
                            </div>
                        </div>
                        <?php if ($model->timer > 0): ?>
                            <div class="col-sm-6">
                                <div class="question-form__step-time">Время <i></i>
                                    <span id="countdown" data-time="<?= $model->timer * 60 * 100; ?>"
                                          class="countdown"><?= $model->asTime(); ?></span>
                                </div>
                            </div>
                            <?= Html::input('hidden', 'time', "0", ['id' => 'timer_value']); ?>
                        <?php endif; ?>
                        <div class="col-xs-12"><hr /></div>
                    </div>
                    <div class="question-form__body">
                        <?php foreach ($questions as $key => $question): ?>
                            <!-- шаг <?= ($key + 1); ?> -->
                            <div class="question-form-step<?php if ($key <= 0): ?> active<?php else: ?> hidden<?php endif; ?>"
                                 data-step="<?= ($key + 1); ?>">
                                <div class="col-sm-12">
                                    <div class="form-sub-title"><?= $question['title']; ?></div>
                                </div>
                                <?php if ($question['src']): ?>
                                    <div class="form-box mr-bottom-40">
                                        <img src="<?= TestingQuestion::getImage($question['src']); ?>" alt="">
                                    </div>
                                <?php endif; ?>
                                <div class="form-box clearfix">
                                    <?php foreach ($question['answers'] as $key2 => $variant): ?>
                                    <?php if ($key2 % 2 == 0 && $key2 > 0): ?>
                                </div>
                                <div class="form-box clearfix">
                                    <?php endif; ?>
                                    <div class="form-box__elem col-sm-6">
                                        <label class="checkbox-box wrap-check">
                                            <?= Html::input(($question['multiple'] == TestingQuestion::MULTIPLE_YES ? 'checkbox' : 'radio'),
                                                $answerModel->formName() . '[' . $question['id'] . '][answer_id][]',
                                                $variant['id']
                                            ); ?>
                                            <label><?= $variant['title']; ?></label>
                                        </label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <!-- /шаг <?= ($key + 1); ?> -->
                            </div>
                        <?php endforeach; ?>
                    </div>

                </div>
                <div class="text-dark col-sm-12 mr-top-40 clearfix">
                    <!-- не показываем кнопку назад в начале -->
                    <a style="display: none;" class="step-btn btn-prev btn btn-default btn-md" href="#">Назад</a>
                    <a <?php if (count($questions) <= 1): ?>style="display: none;"<?php endif; ?>
                       class="step-btn btn-next btn btn-primary btn-md" href="#">Далее</a>
                    <a href="#" class="step-btn btn-next btn-last btn btn-success btn-md" style="float:right;">Завершить</a>
                </div>
                <?= Html::endForm(); ?>
                </div>
            </div>
        </div>
        <aside class="main-aside">
        </aside>
    </div>


<?php $this->beginBlock('modalAnswer'); ?>
    <div id="modalAnswer" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Внимание!</h4>
                </div>
                <div class="modal-body text-black">
                    <p>
                        Имеются пропущенные вопросы.
                    </p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-md btn-block" data-dismiss="modal">Продолжить тестирование</button>
                    <button class="btn btn-default btn-submit btn-md btn-block">Завершить тестирование</button>
                </div>
            </div>

        </div>
    </div>
<?php $this->endBlock(); ?>