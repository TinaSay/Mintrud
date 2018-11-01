<?php

use zima\charts\widgets\ChartJsWidget;

/* @var $this yii\web\View */
/* @var $model zima\charts\models\Chart */

?>

<?= ChartJsWidget::widget([
    'config' => $model->config,
]);
?> 
