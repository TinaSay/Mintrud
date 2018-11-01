<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.07.2017
 * Time: 14:19
 */
use app\widgets\sortable\SortableWidget;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $models \app\modules\questionnaire\models\Question[] */

?>

<?= SortableWidget::widget(
    [
        'items' => $models,
        'attributeContent' => 'title',
        'clientEvents' => [
            'update' => 'function (event, ui) { jQuery(this).sortableWidget({url: \'' . Url::to(['update-all']) . '\'}) }',
        ],
    ]
) ?>


