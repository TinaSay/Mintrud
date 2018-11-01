<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:48
 */

/* @var $this yii\web\View */
/* @var $model \app\modules\council\forms\ResetForm */
/* @var $success boolean */

use app\widgets\alert\AlertWidget;
use yii\captcha\Captcha;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode('Восстановление пароля');
?>

<section class="section section-text section-text-left-col-fix">
    <div class="container section-text-container">
        <div class="row">
            <aside class="left-col aside-left"></aside>
            <main class="center-col main-center">

                <?php if ($success === false) : ?>

                    <?= Html::errorSummary($model, ['class' => 'error']) ?>

                    <!-- Форма замены пароля -->
                    <div class="col-md-6">

                        <?= AlertWidget::widget(); ?>

                        <?= Html::beginForm('', 'post', ['class' => 'content-form']) ?>
                        <h2>Введите новый пароль</h2>
                        <?= Html::activePasswordInput($model, 'password', [
                            'value' => '',
                            'autofocus' => true,
                            'placeholder' => 'Новый пароль',
                        ]) ?>
                        <h2>Введите символы с картинки</h2>
                        <div class="row">
                            <?= Captcha::widget([
                                'model' => $model,
                                'attribute' => 'verifyCode',
                                'captchaAction' => '/lk/login/captcha',
                                'options' => [
                                    'placeholder' => 'Символы',
                                ],
                                'template' => '<div class="text-center col-md-4"><div class="main-captcha-container">{image}</div></div><div class="col-md-6">{input}</div>',
                            ]) ?>
                        </div>
                        <div class="row">
                            <div class="col-md-9">
                                <button class="btn-red fool-cons" type="submit">
                                    <span>ОБНОВИТЬ</span>
                                </button>
                            </div>
                        </div>
                        <?= Html::endForm() ?>
                    </div>
                    <!-- Конец формы замены пароля -->

                <?php elseif ($success === true) : ?>

                    <!-- Блок после формы замены пароля -->
                    <div class="col-md-12">
                        <h2>ваш пароль успешно обновлен</h2>
                        <br>
                        <div class="row">
                            <div class="col-md-6">
                                <a class="btn-red fool-cons"
                                   href="<?= Url::to(['/page/page/route', 'path' => 'index']) ?>">
                                    <span>ВЕРНУТЬСЯ НА ГЛАВНУЮ</span>
                                </a>
                            </div>
                            <div class="col-md-6">
                                <a class="btn-red fool-cons link-auth" href="<?= Url::to('/lk/login/login') ?>">
                                    <span>ВОЙТИ В ЛИЧНЫЙ КАБИНЕТ</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Конец блока замены пароля -->

                <?php endif; ?>

            </main>
        </div>
    </div>
</section>
