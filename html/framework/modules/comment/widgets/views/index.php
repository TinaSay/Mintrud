<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 15.11.15
 * Time: 16:08
 */

use app\modules\comment\assets\CommentAsset;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $list app\modules\comment\models\Comment[] */
/* @var $model app\modules\comment\models\Comment */
/* @var $showForm boolean */
/* @var $authorized boolean */
/* @var $properties [] */

CommentAsset::register($this);
?>
    <a name="comments" id="comments"></a>
    <div class="sub-title-and-link text-black">
        <h3 class="sub-title-and-link__title">Комментарии</h3>
        <ul class="sub-title-and-link__list-link">
            <li class="active">
                <a class="text-black all-comments" data-ajax="1"
                   href="<?= Url::to(['/comment/default/lk-comment-list', 'record_id' => $model->record_id]); ?>">
                    Все
                </a>
            </li>
            <li>
                <a class="text-black my-comments" data-ajax="1"
                   href="<?= Url::to(['/comment/default/lk-comment-list', 'record_id' => $model->record_id, 'my' => 1]); ?>">
                    Только мои
                </a>
            </li>
            <li>
                <a class="text-black link-animate-scroll<?php if (!$showForm): ?> disabled<?php endif; ?>"
                   href="#addComment">
                    Добавить комментарий
                </a>
            </li>
        </ul>
    </div>

    <div class="comment-container-list">
        <?php if ($this->beginCache('comments', $properties)) : ?>
            <?= $this->render('_list.php',
                [
                    'list' => $list,
                    'model' => $model,
                    'showForm' => $showForm,
                    'authorized' => $authorized,
                ]) ?>
            <?php $this->endCache();
        endif; ?>
    </div>
<?php if ($authorized): ?>
    <div class="pd-top-40">
        <div class="bg-gray bg-box bg-box--pd-xs" id="addComment">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 comment-form-container">
                        <?= $this->render('_form', [
                            'model' => $model,
                            'authorized' => $authorized,
                            'parentId' => 0,
                            'showForm' => $showForm,
                        ]); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>