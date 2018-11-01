<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 12:47
 */

use app\widgets\breadcrumbs\BreadcrumbsMandatoryHomeWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */

?>
<div class="breadcrumb-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= BreadcrumbsMandatoryHomeWidget::widget([
                    'homeLink' => [
                        'label' => $this->render('label'),
                        'url' => Url::home(),
                        'encode' => false,
                    ],
                    'links' => $this->params['breadcrumbs'] ?? [],
                ]) ?>
            </div>
        </div>
    </div>
</div>
