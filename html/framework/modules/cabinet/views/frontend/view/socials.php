<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 19.04.18
 * Time: 10:05
 */

/* @var $this yii\web\View */
/* @var $model app\modules\cabinet\models\Client */

/* @var $lastLoginAt \app\modules\cabinet\models\Log|null */

use app\modules\cabinet\widgets\menu\Menu;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode('Активация учетной записи Портала Госуслуг');
$this->params['breadcrumbs'][] = Html::encode('Личный кабинет');
?>

<section class="pd-top-0 pd-bottom-40">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <?= $this->render('//parts/breadcrumbs'); ?>
                <div class="pd-top-0">
                    <h1 class="page-title text-black"><?= $this->title ?></h1>
                    <div class="pd-top-10">
                        <div class="border-block border-block--bg-box mr-top-20 pd-top-35 pd-bottom-60">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php if ($model->hasSocial('esia')):
                                            $social = $model->getSocial('esia');
                                            ?>
                                            <div class="text-black">
                                                <p>
                                                    Ваша учетная запись связана с аккаунтом портала Госуслуг
                                                    <a href="#"><?= $social ? $social->source_id . '@esia.gosuslugi.ru' : '' ?></a>
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-black">
                                                <p>
                                                    Если адрес электронной почты для Портала Госуслуг совпадает с адресом электронной почты для личного кабинета на Портале Минтруда, Вам необходимо осуществить активацию. Для этого выполните авторизацию на Портале Госуслуг.
                                                </p>
                                            </div>
                                            <div class="two-btn two-btn--ix2">
                                                <a href="<?= Url::to([
                                                    '/cabinet/default/oauth',
                                                    'authclient' => 'esia',
                                                ]) ?>"
                                                   class="btn btn-primary btn-lg link-auth-gos mr-top-40"><span><i></i>Авторизоваться на Портале Госуслуг</span></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <aside class="main-aside pd-top-55">
                <?= Menu::widget() ?>
                <?php if (!is_null($lastLoginAt)) : ?>
                    <div class="border-block">
                        <h4 class="text-uppercase text-prime pd-bottom-15">Ваша активность</h4>
                        <div class="pd-top-5 text-black text-xs">
                            Последний раз вы заходили в личный кабинет
                        </div>
                        <div class="pd-bottom-10 text-black text-bold">
                            <?= Yii::$app->getFormatter()->asDatetime($lastLoginAt->created_at,
                                'dd MMMM yyyy года в HH:mm') ?>
                        </div>
                    </div>
                <?php endif; ?>
            </aside>
        </div>
    </div>
</section>
