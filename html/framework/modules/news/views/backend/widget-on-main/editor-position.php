<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.06.2017
 * Time: 18:06
 */
use app\widgets\sortable\SortableWidget;
use yii\helpers\Url;

/** @var $this */
/** @var $models \app\modules\news\models\WidgetOnMain[] */

$this->title = Yii::t('system', 'Widget On Main');
$this->params['breadcrumbs'][] = $this->title;

?>

<?= SortableWidget::widget(
    [
        'items' => $models,
        'attributeContent' => 'title',
        'clientEvents' => [
            'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['update-all']) . '\'}) }',
        ],
    ]
); ?>