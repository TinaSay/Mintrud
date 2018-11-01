<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 05.09.2017
 * Time: 17:51
 */


use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $searchModel \app\modules\media\models\search\MediaSearch */

?>

<div class="border-block">
    <?= Html::beginForm('/media/media/search', 'get', ['class' => 'filter-post-list', 'id' => 'media-search']) ?>
    <p>Ключевые слова</p>
    <?= Html::activeTextInput($searchModel, 'words', ['class' => 'filter-ell form-control']) ?>
    <p>Тип</p>
    <?= Html::activeDropDownList(
        $searchModel,
        'type',
        $searchModel::asDropDown(),
        [
            'class' => 'filter-ell selectpicker',
            'id' => 'media-directory',
            'prompt' => 'Все',
        ]
    ) ?>
    <?= Html::endForm(); ?>

</div>
