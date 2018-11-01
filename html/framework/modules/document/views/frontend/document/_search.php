<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08.08.2017
 * Time: 16:57
 */

use app\modules\document\helpers\DescriptionDirectoryHelper;
use app\modules\organ\helpers\OrganHelper;
use app\modules\typeDocument\helpers\TypeHelper;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $searchModel \app\modules\document\models\search\DocumentSearch */

?>
<?= Html::beginForm('', 'get', ['class' => 'filter-post-list', 'id' => 'document-search']) ?>
    <p>Ключевые слова</p>
<?= Html::activeTextInput($searchModel, 'words', ['class' => 'filter-ell form-control']) ?>
    
    <?php 
    /*
    <p>Сортировать</p>
    <div class="filter-post-list filter-ell">
        <label class="checkbox-box wrap-check">
            <input type="radio" name="sorting" value="0" checked><label>по дате</label>
        </label>
        <label class="checkbox-box wrap-check">
            <input type="radio" name="sorting" value="1"><label>по релевантности</label>
        </label>
    </div>
    */
    ?>

    <p>Номер документа</p>
<?= Html::activeTextInput($searchModel, 'number', ['class' => 'filter-ell form-control']) ?>
    <p>Тип документа</p>
<?= Html::activeDropDownList(
    $searchModel,
    'type_document_id',
    TypeHelper::getTypeDropDown(),
    [
        'prompt' => 'Все',
        'class' => 'filter-ell selectpicker document-type'
    ]
) ?>
    <p>Действительность</p>
<?= Html::activeDropDownList(
    $searchModel,
    'reality',
    $searchModel->getRealityArray(),
    [
        'prompt' => 'Все',
        'class' => 'filter-ell selectpicker'
    ]
) ?>
    <p>Направление</p>
<?= Html::activeDropDownList(
$searchModel,
'direction_id',
    DescriptionDirectoryHelper::getDescriptionDirectoryDropDonw(),
['prompt' => 'Все',
'class' => 'filter-ell selectpicker']
) ?>
    <p>Принявший орган</p>
<?= Html::activeDropDownList(
$searchModel,
'organ_id',
    OrganHelper::getOrganDropDown(),
['prompt' => 'Все',
'class' => 'filter-ell selectpicker']
) ?>
    <p>Период публикации</p>
<div class="inline-wrap date-wiget">
    <div class="inline-ell">
        <?= Html::activeTextInput(
        $searchModel,
        'beginCreated',
        ['class' => 'filter-ell form-control',
        'placeholder' => 'дд/мм/гггг',
        'id' => 'date-start',]
        ) ?>
    </div>
    <div class="inline-ell">
        <?= Html::activeTextInput(
        $searchModel,
        'finishCreated',
        ['class' => 'filter-ell form-control',
        'placeholder' => 'дд/мм/гггг',
        'id' => 'date-end',]
        ) ?>
    </div>
</div>
<p>Год</p>
<div class="date-wiget">
        <?= Html::activeTextInput(
            $searchModel,
            'yearCreated',
            ['class' => 'filter-ell form-control',
                'placeholder' => 'гггг',
                'id' => 'date-year',]
        ) ?>
</div>
<?= Html::endForm(); ?>
