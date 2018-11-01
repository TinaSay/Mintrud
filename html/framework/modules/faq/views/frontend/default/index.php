<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.12.17
 * Time: 14:57
 */

use app\modules\favorite\widgets\AddFavoriteWidget;
use krok\extend\widgets\script\ScriptWidget;

/** @var $this yii\web\View */
/** @var $list \app\modules\faq\models\FaqCategory[] */
/** @var $lastUpdated string */
/** @var $breadcrumbs array */


$this->title = 'Часто задаваемые вопросы';

$this->params['breadcrumbs'] = $breadcrumbs;

$this->params['share-page'] = true;

$this->beginBlock('add-favorite');
echo AddFavoriteWidget::widget(
    [
        'addView' => 'breadcrumbs/add-favorite',
        'existView' => 'breadcrumbs/exist-favorite',
    ]
);
$this->endBlock();
?>
<?php ScriptWidget::begin(); ?>
<script>

    $('form.search-filter').on('submit', function (e) {
        e.preventDefault();
    });

    $("#search").on('keyup', function () {
        var _this = this;

        $.each($(".faq-list > li > ul > li > a"), function () {
            if ($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1) {
                $(this).parent('li').hide().removeClass('item-search');
            } else {
                $(this).parent('li').show().addClass('item-search');
            }
        });
        $.each($(".faq-list > li > ul"), function () {
            if ($(this).find('.item-search').length > 0) {
                $(this).slideDown(0);
                $(this).closest('.faq-list__category').addClass('open').show();
            }
            else {
                $(this).slideUp(0);
                $(this).closest('.faq-list__category').removeClass('open').hide(0);
            }
        });
    });


    $('.faq-list > li > a').on('click', function () {
        var parent = $(this).closest('li'),
            content = parent.find('> ul');
        if (parent.hasClass('open')) {
            parent.removeClass('open');
            content.slideUp(300);
        }
        else {
            parent.addClass('open');
            content.slideDown(300);
        }
        return false;
    });

    $('.faq-list > li > ul > li > a').on('click', function(){
        var _this = $(this),
            parent = _this.parent('li');
        
        if (parent.hasClass('open')){
            parent.removeClass('open');
            _this.next('.faq-list__item-text').slideUp(300);
            return false;
        };
        
        $('.faq-list > li > ul').find('li').removeClass('open');
        parent.addClass('open');
        $('.faq-list__item-text').slideUp(300); 
        _this.next('.faq-list__item-text').slideDown(300); 
        return false;
    });

</script>
<?php ScriptWidget::end(); ?>
<!-- page content -->
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            </div>
        </div>
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title; ?></h1>
                <p class="page-date text-light"><?= Yii::$app->formatter->asDate($lastUpdated); ?></p>
                <div class="post-content text-dark">


                    <div class="wrap-search-form">
                        <form method="get" class="search-filter">
                            <input id="search" type="text" class="form-control field-icon-loupe" name="term" value=""
                                   autocomplete="off" placeholder="Введите ключевые слова">
                        </form>
                    </div>
                    <ul class="i-list faq-list">
                        <?php foreach ($list as $category): ?>
                            <li class="faq-list__category">
                                <a href="#" class="title-small"><?= $category['title']; ?></a>
                                <ul>
                                    <?php foreach ($category['faqs'] as $model): ?>
                                        <li>
                                            <a href="#"><?= $model['question']; ?></a>
                                            <div class="faq-list__item-text">
                                                <div class="item-text-title">Ответ</div>
                                                <?= $model['answer']; ?>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <aside class="main-aside">
                <div class="border-block block-arrow">
                    <p class="text-light">Дата обновления:</p>
                    <p class="pd-bottom-15"><?= Yii::$app->formatter->asDate($lastUpdated); ?></p>
                </div>
                <?= \app\modules\ministry\widgets\MinistryMenuWidget::widget(); ?>
            </aside>
        </div>
    </div>
</section>
<!-- page content end -->
