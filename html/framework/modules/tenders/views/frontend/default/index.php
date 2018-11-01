<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 20:18
 */

/** @var $this \yii\web\View */
/** @var $list \app\modules\tenders\models\Tender[] */
/** @var $pagination \yii\data\Pagination */

$this->title = 'Конкурсы и тендеры';

$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="clearfix">
    <div class="main">
        <div class="page-view">
            <h1 class="page-title text-black"><?= $this->title ?></h1>
            <div class="page-content pd-top-40 pd-bottom-70">
                <?php if (!empty($list)): ?>
                <?php foreach ($list as $model): ?>

                    <div class="post-list">
                        <table width="100%" cellspacing="0" cellpadding="0">
                            <tbody>
                            <tr>
                                <td valign="top" class="tenderTd" width="20%">
                                    <dl>
                                        <dt>
                                            <strong class="text-black"><?= $model->auction ?></strong>
                                        </dt>
                                        <dd></dd>
                                        <dt>
                                            <span class="status text-light lh-normal"><?= $model->getStatus(); ?></span>
                                        </dt>
                                        <dd>
                                            <strong>
                                                <?= Yii::$app->formatter->asDecimal($model->orderSum, 2); ?>
                                            </strong>
                                            <br><span class="currency">Российский рубль</span>
                                        </dd>
                                    </dl>
                                </td>
                                <td valign="top" class="descriptTenderTd">
                                    <dl>
                                        <dt>
                                        <span class="order text-black">
                                            № <?= $model->regNumber; ?>
                                        </span>
                                        </dt>
                                        <dd></dd>
                                        <dd>
                                            <?= $model->title; ?>
                                        </dd>
                                        <dd class="pd-top-10">
                                            Идентификационный код закупки(ИКЗ):
                                            <dl class="text-light"><?= $model->orderIdentity; ?></dl>
                                        </dd>
                                    </dl>
                                </td>
                                <td width="20%" valign="top" class="amountTenderTd other-box">
                                    <ul>
                                        <li>
                                            <label>Размещено:</label>
                                            <span class="page-date text-light">
                                            <?= Yii::$app->formatter->asDate($model->createdAt, 'dd.MM.yyyy'); ?>
                                        </span>
                                        </li>
                                        <li>
                                            <label>Обновлено:</label> <span class="page-date text-light">
                                            <?= Yii::$app->formatter->asDate($model->updatedAt, 'dd.MM.yyyy'); ?>
                                        </span>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
                <div class="wrap-pagination">
                    <?= $this->render('//parts/pagination', ['pagination' => $pagination]) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
