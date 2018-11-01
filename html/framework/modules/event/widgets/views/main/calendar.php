<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 10:58
 */

use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dependency \yii\caching\TagDependency */
/** @var $context \app\modules\event\widgets\CalendarWidget */

$context = $this->context;
?>

<!-- <div class="datepiker-wrap">
    <div id="tempust" class="tempust-wrap"></div>
</div> -->

<?php if ($this->beginCache(__FILE__ . __LINE__, [
    'duration' => 1 * 60 * 60,
    'dependency' => $dependency,
    'variations' => [
        Yii::$app->language,
        $context->dateBegin->format('Y-m-d'),
        $context->dateFinish->format('Y-m-d'),
    ],
])) : ?>
    <div class="calendar" id="main-calendar-widget">
        <div class="calendar-wrapper">
            <div class="row calendar-selects">
                <div class="col-xs-6">
                    <?= Html::dropDownList(
                        'year',
                        null,
                        $context->getYearDropDown(),
                        [
                            'class' => 'selectpicker b-select',
                            'id' => 'calendar-select-year',
                        ]
                    ) ?>
                    <?= Html::dropDownList(
                        'month',
                        null,
                        $context->getMonthDropDown(),
                        [
                            'class' => 'selectpicker b-select',
                            'id' => 'calendar-select-month',
                        ]
                    ) ?>
                </div>
                <div class="col-xs-6 text-right">
                    <a class="btn btn-calendar" style="display: none;" href="#">Следующее событие</a>
                    <div class="calendar--prev-arrow">
                        <div class="fa fa-angle-left"></div>
                    </div>
                    <div class="calendar--next-arrow">
                        <div class="fa fa-angle-right"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="calendar-container">
            <div class="calendar-wrapper">
                <div class="calendar-content">
                    <?= $context->renderDay() ?>
                </div>
                <?= $context->renderEvent(); ?>
            </div>
        </div>
    </div>
    <?php $this->endCache(); ?>
<?php endif; ?>
