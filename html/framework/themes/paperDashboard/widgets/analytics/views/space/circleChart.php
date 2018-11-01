<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.08.17
 * Time: 17:48
 */

/* @var $this yii\web\View */
/* @var $percent float|int */
/* @var $total int */
/* @var $used int */

?>
<div class="card card-circle-chart" data-background-color="green">
    <div class="card-header text-center">
        <h5 class="card-title">Занято места на диске сервера</h5>
        <p class="description">Общий объем диска: <?= Yii::$app->getFormatter()->asShortSize($total, 1) ?></p>
    </div>
    <div class="card-content">
        <div id="chartDashboard" class="chart-circle" data-percent="<?= $percent * 100 ?>">
            <div class="chart-circle__info">
                <span>
                    <span class="percent"><?= Yii::$app->getFormatter()->asPercent($percent, 1) ?></span>
                    <br>
                    <span class="used"><?= Yii::$app->getFormatter()->asShortSize($used, 1) ?></span>
                </span>
            </div>
        </div>
    </div>
</div>
