<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 29.06.2017
 * Time: 19:18
 */
use app\modules\document\models\WidgetOnMain;
use app\modules\typeDocument\models\Type;
use app\widgets\sortable\SortableWidget;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models WidgetOnMain[] */

?>

<?= SortableWidget::widget(
    [
        'items' => $models,
        'attributeContent' => function (array $model) {
            return Type::findOne($model['type_document_id'])->title;
        },
        'clientEvents' => [
            'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['update-all']) . '\'}) }',
        ],
    ]
) ?>