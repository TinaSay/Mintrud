<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 13:53
 */


/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */
/** @var TagInterface $inverse */
/** @var $models Relation[] */

use app\modules\tag\interfaces\TagInterface;
use app\modules\tag\models\Relation;
use yii\widgets\ListView;

$this->title = 'Результаты поиска';

$this->params['breadcrumbs'][] = ['label' => $this->title];


$models = $dataProvider->getModels();
?>

<div class="clearfix">
    <div class="main">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <div class="pd-bottom-70 pd-top-30">
            <?php if (!empty($models)): ?>
                <div class="tags-result-list">
                    <?= (ListView::begin(['dataProvider' => $dataProvider,]))->renderSummary() ?>
                    <?php foreach ($models as $model): ?>
                        <?php if (!is_null($query = $model->findModel()) && (($inverse = $query->one()) instanceof TagInterface)): ?>
                            <div>
                                <a href="<?= $inverse->viewUrl() ?>"><?= $inverse->getTitle() ?></a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <div class="wrap-pagination">
                    <?= $this->render('//parts/pagination', ['pagination' => $dataProvider->getPagination()]) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <aside class="main-aside"></aside>
</div>
