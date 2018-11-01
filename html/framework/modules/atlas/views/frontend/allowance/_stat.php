<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.07.17
 * Time: 11:01
 */

/** @var $this yii\web\View */
/** @var $region \app\modules\atlas\models\AtlasDirectorySubjectRf */
/** @var $model \app\modules\atlas\models\AtlasAllowance */
/** @var $reg_id int */

?>
<h2><?= $region['title']; ?></h2>

<div class="atlas-content-table"> data-reg_id="<?= $reg_id; ?>" class="stat">
    <?php if ($model): ?>
        <?php if ($model->federal): ?>
            <div class="row">
                <div class="col-lg-12 federal">
                    <h4>Федеральные пособия и выплаты</h4>
                    <?= $model->federal; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($model->regional): ?>
            <div class="row">
                <div class="col-lg-12 federal">
                    <h4>Региональные пособия и выплаты</h4>
                    <?= $model->regional; ?>
                </div>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-12 federal">
                <p>Нет данных.</p>
            </div>
        </div>
    <?php endif; ?>
</div>
