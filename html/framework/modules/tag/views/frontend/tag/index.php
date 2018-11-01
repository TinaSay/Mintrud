<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 13:41
 */
use app\modules\tag\models\Tag;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\helpers\Url;

$this->title = 'Список тегов';

$this->params['breadcrumbs'][] = ['label' => $this->title]

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

?>

<div class="row">
	<div class="main col-md-12">
		<h1 class="page-title text-black"><?= $this->title ?></h1>
		<div class="pd-bottom-70 pd-top-30">
			<?= ListView::widget([
				'options' => [
					'class' => 'tags-list'
				],
			    'dataProvider' => $dataProvider,
			    'itemView' => function (Tag $model) {
			        return Html::a($model->name, ['/tags/relation/index', 'id' => $model->id]);
			    }
			]) ?>
		</div>
	</div>
</div>
