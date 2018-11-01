<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <section class="pd-top-0 pd-bottom-30">
        <div class="container">
            <div class="row">
                <div class="main col-md-12">
                    <div class="text-black pd-top-80 pd-bottom-120">
                        <h1 class="page-title-sm page-title pd-bottom-30"><?= nl2br(Html::encode($message)) ?></h1>
                        <p class="h4 pd-bottom-95"><?= Html::encode($this->title) ?></p>
                        <p class="pd-bottom-30">Вы можете вернуться на <a class="underline" href="/">главную страницу</a> или воспользоваться поиском:</p>
                        <div class="wrap-search-form">
                            <?= Html::beginForm(['/search'], 'get') ?>
                            <?= Html::textInput('term', $term, ['class' => 'form-control']) ?>
                            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary btn-lg search-btn']) ?>
                            <?= Html::endForm() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
</div>
