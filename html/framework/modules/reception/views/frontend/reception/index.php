<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 19.07.17
 * Time: 17:35
 */

use app\modules\cabinet\widgets\menu\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var $this yii\web\View */
/** @var $list \app\modules\reception\models\Appeal[] */

$this->title = Html::encode('Обращения');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');

?>
<section class="pd-top-0 pd-bottom-40">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <?= $this->render('//parts/breadcrumbs'); ?>
                <div class="pd-top-0">
                    <h1 class="page-title text-black"><?= $this->title; ?></h1>
                    <div class="info-link-block pd-top-20">
                        <p>
                            <a href="<?= Url::to(['/reception/order']); ?>">
                                <i>!</i>
                                <span>Порядок приема и рассмотрения обращений граждан</span>
                            </a>
                        </p>
                        <p>
                            <a href="<?= Url::to(['/reception/form']); ?>">
                                <i>?</i>
                                <span>Подать обращение</span>
                            </a>
                        </p>
                    </div>
                    <div class="pd-top-10">
                        <?php if ($list) : ?>
                            <div class="post-list--style-i">
                                <?php foreach ($list as $model): ?>
                                    <div class="post-list text-black">
                                        <ul class="post-list__list">
                                            <li>
                                                Дата и время обращения:
                                                <span>
                                                    <?= Yii::$app->formatter->asDate($model->created_at,
                                                        'd MMMM YYYY года, HH:mm'); ?>
                                                </span>
                                            </li>
                                            <li>Номер обращения: <span><?= $model->reg_number; ?></span></li>
                                            <li>Тема обращения: <span><?= $model->theme; ?></span></li>
                                            <li class="post-list__status">
                                                Статус: <span><?= $model->getStatus(); ?></span>
                                            </li>
                                        </ul>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <aside class="main-aside pd-top-55">
                <?= Menu::widget() ?>
            </aside>
        </div>
    </div>
</section>
