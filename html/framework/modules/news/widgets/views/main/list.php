<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 17:36
 */
use app\modules\news\widgets\TabOnMainWidget;

/** @var $this \yii\web\View */
/** @var $widgets \app\modules\news\models\WidgetOnMain[] */

$all = 'all';

?>
<?php if (!empty($widgets)): ?>
    <section class="pd-top-80 pd-bottom-20">
        <div class="container">
            <div class="tabs-nav-wrap text-black clearfix">
                <h3 class="section-head pull-left">Картина дня</h3>
                <ul id="day_map_tabs_nav" class="nav nav-tabs pull-right">
                    <li class="custom-tab-item active" data-content="day_map_<?= $all ?>">
                        <a>Все</a>
                    </li>
                    <?php foreach ($widgets as $widget): ?>
                        <li class='custom-tab-item' data-content="day_map_<?= $widget->id ?>">
                            <a><?= $widget->title ?></a>
                        </li>
                    <?php endforeach; ?>
                    <li class="tabs-container dropdown">
                        <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                        <div class="tabs-container__content dropdown-menu"></div>
                    </li>
                </ul>
            </div>

            <div id="day_map_tabs_content" class="pd-top-60">

                <?= TabOnMainWidget::widget(['numberTab' => $all, 'active' => true]) ?>
                <?php foreach ($widgets as $widget): ?>
                    <?= TabOnMainWidget::widget(['numberTab' => $widget->id, 'directory_id' => $widget->directory_id]) ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>