<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 12:21
 */

/** @var $this yii\web\View */
/* @var $pagination yii\data\Pagination */

?>
<?php if ($pagination->getPageCount() > 1) : ?>
    <?= \yii\widgets\LinkPager::widget(
        [
            'pagination' => $pagination,
            'maxButtonCount' => 5,
            'options' => ['class' => 'custom-pagination text-dark'],
            'linkOptions' => ['class' => ''],
            'disabledListItemSubTagOptions' => ['tag' => 'a'],
            'disabledPageCssClass' => 'disabled',
            'activePageCssClass' => 'active',
            'prevPageLabel' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
        ]
    ); ?>
<?php endif; ?>
