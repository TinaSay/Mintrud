<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 13.07.2017
 * Time: 17:34
 */
/** @var $this \yii\web\View */
/** @var $model \app\modules\document\models\Document */

use app\modules\magic\models\Magic;
use app\modules\magic\widgets\MagicManageWidget;

?>

<?=
MagicManageWidget::widget(
    [
        'model' => new Magic(['scenario' => 'many']),
        'attribute' => Magic::ATTRIBUTE,
        'fileInputOptions' => [
            'multiple' => true,
        ],
        'attributes' => [
            'module' => $model::className(),
            'group_id' => 0,
            'record_id' => $model->id,
        ],
    ]
); ?>


