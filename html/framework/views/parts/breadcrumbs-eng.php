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
<?php if (isset($this->params['breadcrumbs']) && is_array($this->params['breadcrumbs'])): ?>
    <div class="breadcrumb-section">
        <div class="container">
            <div class="clearfix">
                <div class="main">
                    <?= BreadcrumbsMandatoryHomeWidget::widget([
                        'homeLink' => [
                            'label' => '',
                            'url' => Url::to(['/eng']),
                            'class' => 'home',
                        ],
                        'links' => $this->params['breadcrumbs'],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>