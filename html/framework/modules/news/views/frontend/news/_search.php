<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 05.09.2017
 * Time: 17:51
 */


use app\modules\directory\help\DirectoryDropDown;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\news\models\News;
use app\modules\tag\widgets\ListWidget;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $searchModel \app\modules\news\models\search\NewsSearch */

?>

<div class="border-block">
    <?= Html::beginForm('', 'get', ['class' => 'filter-post-list', 'id' => 'news-search']) ?>
    <p>Ключевые слова</p>
    <?= Html::activeTextInput($searchModel, 'words', ['class' => 'filter-ell form-control']) ?>
    <p>Направление</p>
    <?= Html::activeDropDownList(
        $searchModel,
        'directory_id',
        DirectoryDropDown::list(
            [],
            TypeInterface::TYPE_NEWS
        ),
        [
            'class' => 'filter-ell selectpicker',
            'id' => 'news-directory',
            'prompt' => 'Все',
            'options' => DirectoryDropDown::getSelectOptions(),
        ]
    ) ?>
    <?= Html::endForm(); ?>

    <?= ListWidget::widget(['model' => News::class]) ?>
</div>
