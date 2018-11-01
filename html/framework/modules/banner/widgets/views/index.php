<?php

/* @var $this yii\web\View */
/* @var $banners app\modules\banner\models\BannerCategory[] */

$firstId = !empty($banners[0]->id) ? $banners[0]->id : null;
?>

<section id="sectionServices" class="pd-top-70 pd-bottom-70" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="tabs-nav-wrap text-black clearfix">
            <ul class="nav nav-tabs nav-tabs-uslugi flax-wrap pull-right" role="tablist" id="navbar-uslugi">
                <?php foreach ($banners as $banner) : ?>
                    <li class="custom-tab-item <?= $firstId == $banner->id ? 'active' : '' ?>">
                        <a href="#tab_content_<?= $banner->id ?>" role="tab" data-toggle="tab" aria-controls="profile"
                           aria-expanded="false" style="white-space: normal">
                            <?= $banner->title ?>
                        </a>
                    </li>
                <?php endforeach; ?>

                <li class="tabs-container dropdown">
                    <div class="tabs-container__btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></div>
                    <div class="tabs-container__content dropdown-menu"></div>
                </li>
            </ul>
        </div>

        <div class="tab-content pd-bottom-40">
            <?php foreach ($banners as $banner) : ?>
                <div class="tab-pane fade <?= $firstId == $banner->id ? 'active in' : '' ?>" role="tabpanel" id="tab_content_<?= $banner->id ?>" aria-labelledby="home-tab">
                    <div class="row flax-wrap">
                        <?php if (isset($banner->relatedRecords['children'])) : ?>
                            <?php foreach ($banner->relatedRecords['children'] as $record) : ?>
                                <div class="col-xs-12 col-sm-12 col-md-4 services-box">
                                    <a href="<?= $record->url ?>" target="_blank" class="jumbotron friend-block">
                                        <h4><?= $record->title ?></h4>
                                        <div class="svg-icon icon-friend"></div>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-md-12 text-center">
            <a class="link-more text-black text-bold" id="servicesMoreBtn" href="#"><span>Показать больше</span>
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>