<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.07.2017
 * Time: 13:52
 */

/** @var $this \yii\web\View */
/** @var $models \app\modules\document\models\Document[] */
/** @var $context \app\modules\document\widgets\DocumentOnMainListWidget */

$context = $this->context;

?>

<div class="tab-pane tab-pane-docs <?= $context->getClassActive() ?>" role="tabpanel"
     id="tab_doc_<?= $context->getTabId(); ?>"
     aria-labelledby="home-tab">
    <div class="row flax-wrap">
        <?php foreach ($models as $model): ?>
            <div class="col-sm-12 col-md-4 mr-bottom-30">
                <a href="<?= $model->getUrl() ?>" class="text-black docs-preview__box border-block-sm">
                    <p class="docs-preview-title text-black">
                        <?= $model->title ?>
                    </p>
                    <p class="text-base docs-preview-text">
                        <?= $model->announce ?>
                    </p>
                </a>
            </div>
        <?php endforeach; ?>
    </div>
</div>