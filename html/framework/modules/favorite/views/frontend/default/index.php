<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 23.07.17
 * Time: 9:13
 */

/* @var $this yii\web\View */

/* @var $list \app\modules\favorite\dto\frontend\ListDto[] */

use app\modules\cabinet\widgets\menu\Menu;
use yii\helpers\Html;

$this->title = Html::encode('Избранное');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>
<section class="pd-top-0 pd-bottom-40">
    <div class="container">
        <div class="main">
            <?= $this->render('//parts/breadcrumbs'); ?>
            <div class="pd-top-0">
                <h1 class="page-title text-black"><?= $this->title ?></h1>
                <div class="pd-top-10">
                    <div class="post-list--style-i">
                        <?php if ($list): ?>
                            <?php foreach ($list as $row) : ?>
                                <div class="post-list text-black post-list--with-delete">
                                    <a href="<?= $row->getUrl() ?>" class="post-name post-name--sm text-black">
                                        <?= $row->getTitle() ?>
                                    </a>
                                    <a class="text-prime post-list__link-bottom" href="<?= $row->getUrl() ?>">
                                        <?= Yii::$app->getRequest()->getHostName() ?>
                                    </a>
                                    <a class="link-delete" data-ajax="" href="<?= $row->getDeleteUrl() ?>"></a>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p>Нет материалов, добавленных в "Избранное".</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <aside class="main-aside pd-top-55">
            <?= Menu::widget() ?>
            <div class="border-block">
                <h4 class="text-uppercase text-prime pd-bottom-15">Как добавить материал в избранное?</h4>
                <div class="img-favorite">
                    <img src="/static/default/img/image/favorite.jpg" alt=""/>
                </div>
                <div class="text-black text-md">
                    В правой части страницы любого информационного материала нажмите на иконку «звездочка» и он
                    сохранится в списке «Избранное» Вашего личного кабинета.
                </div>
            </div>
        </aside>
    </div>
</section>
