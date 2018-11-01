<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.07.2017
 * Time: 13:47
 */
use app\modules\document\widgets\DocumentOnMainListWidget;

/** @var $this \yii\web\View */
/** @var $tabs \app\modules\document\models\WidgetOnMain[] */

$firstTab = true;
$firstList = true;
?>

<?php if (!empty($tabs)): ?>
    <section class="pd-top-70 pd-bottom-20">
        <div class="container">
            <div class="tabs-nav-wrap text-black clearfix">
                <h3 class="section-head pull-left">Документы</h3>
                <ul class="nav nav-tabs pull-right" id="navbar-doc">
                    <?php foreach ($tabs as $tab): ?>
                        <li class="<?= $firstTab === true ? 'active' : null ?> custom-tab-item">
                            <a href="#tab_doc_<?= $tab->id ?>" role="tab" data-toggle="tab" aria-controls="home"
                               aria-expanded="true">
                                <?= $tab->title ?>
                            </a>
                        </li>
                        <?php
                        $firstTab = false;
                    endforeach; ?>
                    <li class="tabs-container dropdown">
                        <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                        <div class="tabs-container__content dropdown-menu"></div>
                    </li>
                </ul>
            </div>

            <div class="tab-content pd-top-60">
                <?php foreach ($tabs as $tab): ?>
                    <?= DocumentOnMainListWidget::widget([
                        'tab' => $tab,
                        'active' => $firstList,
                    ]) ?>
                    <?php
                    $firstList = false;
                endforeach; ?>

                <div class="text-center">
                    <a class="link-more text-black text-bold" href="/docs">Все документы <i class="fa fa-angle-right"
                                                                                        aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>