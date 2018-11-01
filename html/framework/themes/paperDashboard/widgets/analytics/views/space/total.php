<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.07.17
 * Time: 17:44
 */

/* @var $this yii\web\View */
/* @var $receive string */
?>
<div class="card card-sm-pd">
    <div class="card-content">
        <div class="row">
            <div class="col-xs-5">
                <div class="icon-big icon-color-1 text-center"><i class="ti-harddrives"></i></div>
            </div>
            <div class="col-xs-7">
                <div class="numbers">
                    <p>Всего места</p>&nbsp;<?= Yii::$app->getFormatter()->asShortSize($receive, 2) ?>
                </div>
            </div>
        </div>
    </div>
</div>