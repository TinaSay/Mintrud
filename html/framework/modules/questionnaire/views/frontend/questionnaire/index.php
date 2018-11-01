<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 13:18
 */
use app\modules\questionnaire\models\Questionnaire;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

?>

<div class="row">
    <div class="col-md-12">
        <?= \yii\widgets\ListView::widget(
            [
                'dataProvider' => $dataProvider,
                'itemView' => function (Questionnaire $model) {
                    return Html::a($model->title, ['/questionnaire/question/question', 'id' => $model->id]);
                }
            ]
        ) ?>
    </div>
</div>
