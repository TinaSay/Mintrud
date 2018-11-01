<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 20.12.17
 * Time: 12:05
 */

use yii\widgets\ListView;

/** @var $this yii\web\View */
/** @var $dataProvider \yii\data\ArrayDataProvider */
/** @var $type string */

?>
<?= ListView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['class' => 'list-group list-group-services'],
    'itemView' => '_item',
    'viewParams' => ['type' => $type],
    'layout' => '{items}',
]) ?>

<div class="wrap-pagination">
    <?= $this->render('//parts/pagination', ['pagination' => $dataProvider->pagination]) ?>
</div>
