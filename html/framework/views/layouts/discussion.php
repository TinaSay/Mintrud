<?php

use app\assets\AppAsset;
use yii\helpers\Html;

/** @var $this \yii\web\View */
/** @var $content string */

AppAsset::register($this);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Html::encode($this->title) ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>

</head>
<body<?= \app\widgets\blind\BlindWidget::widget() ?>>
<?php $this->beginBody() ?>
<?= $this->render('//parts/header') ?>

<!-- page content -->
<?= $content ?>
<!-- page content end -->

<?= $this->render('//parts/footer') ?>

<?php if ($this->blocks):
    foreach ($this->blocks as $block):
        print $block;
    endforeach;
endif; ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage(); ?>
