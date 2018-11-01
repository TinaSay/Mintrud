<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 12:07
 */

use app\modules\council\assets\CouncilSettingsAsset;
use app\widgets\alert\AlertWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\modules\council\models\CouncilMember */

$this->title = Html::encode('Уведомления');

$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Discussion'), 'url' => ['/lk/discussion/index']];
$this->params['breadcrumbs'][] = Yii::t('system', 'Settings');

CouncilSettingsAsset::register($this);

?>
<!-- page content -->
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <h1 class="page-title text-black"><?= $this->title; ?></h1>
                <?= AlertWidget::widget(); ?>
                <div class="post-content text-black pd-top-30">
                    <p>
                        Вы можете добавить один или несколько дополнительных адресов, на которые будут поступать
                        уведомления о проводимых общественных обсуждениях на сайте Министерства труда и социальной защиты РФ.
                    </p>
                </div>

                <ul class="email-list pd-top-30 text-black" data-url="<?= Url::to(['remove-email']); ?>">
                    <li class="template hidden">
                                <span class="email-list__email"><span></span>
                                    <a class="link-delete" href="#"></a>
                                </span>
                    </li>
                    <?php if ($emails = $model->getAdditionalEmailAsArray()): ?>
                        <?php foreach ($emails as $email): ?>
                            <li>
                                <span class="email-list__email"><span><?= $email; ?></span>
                                    <a class="link-delete" href="#"></a>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>

                <div class="pd-bottom-80">
                    <div class="bg-gray bg-box bg-box--pd-sm">
                        <div class="container-fluid">
                            <div class="row">
                                <?= Html::beginForm(['add-email'], 'post', ['class' => "form-add-email"]); ?>
                                <div class="col-lg-10">
                                    <h3 class="text-black form-title">Добавить нового получателя</h3>
                                    <div class="error-message"></div>
                                </div>
                                <div class="col-lg-7 col-md-10">
                                    <div class="form-group form-group--placeholder-fix">
                                        <label class="placeholder" for="inputEmail">Укажите email</label>
                                        <input name="email" required type="email" class="form-control"
                                               id="inputEmail">
                                    </div>
                                    <div class="two-btn">
                                        <button type="submit" class="btn btn-primary btn-lg two-btn__elem">
                                            Добавить
                                        </button>
                                    </div>
                                </div>
                                <?= Html::endForm(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?= $this->render('_toolbar'); ?>
        </div>
    </div>
</section>
<!-- page content end -->