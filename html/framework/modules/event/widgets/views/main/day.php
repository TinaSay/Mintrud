<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 11:53
 */
/** @var $this \yii\web\View */
/** @var $datePeriod DatePeriod */
/** @var $context \app\modules\event\widgets\CalendarWidget */

$context = $this->context;
?>


<?php foreach ($datePeriod as $date): ?>
    <?php /** @var $date DateTime */ ?>
    <div class="day<?= $context->getClasses($date) ?>" data-year="<?= $date->format('Y'); ?>"
         data-month="<?= $date->format('n'); ?>" data-date="<?= $date->format('Y-m-d'); ?>">
        <div class="day-header"><?= Yii::$app->formatter->asDate($date, 'eeeeee') ?></div>
        <div class="day-content"><?= $date->format('d'); ?></div>
    </div>
<?php endforeach; ?>