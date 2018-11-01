<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 12:35
 */

use app\modules\comment\widgets\CommentWidget;
use app\modules\council\widgets\CouncilDiscussionVoteWidget;
use app\modules\magic\widgets\MagicWidget;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \app\modules\council\models\CouncilDiscussion */
/* @var $voted bool */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('system', 'Discussion'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
    <section class="pd-top-0 pd-bottom-40">
        <div class="container">
            <div class="clearfix">
                <div class="main">
                    <?= $this->render('//parts/breadcrumbs'); ?>

                    <div class="pd-top-0">
                        <h1 class="page-title text-black"><?= $model->title; ?></h1>
                        <div class="pd-top-25 post-content text-black">
                            <?= $model->text; ?>
                        </div>

                        <?= MagicWidget::widget(
                            [
                                'tpl' => 'discussion_files',
                                'options' => [
                                    'module' => $model::className(),
                                    'group_id' => 0,
                                    'record_id' => $model->id,
                                ],
                            ]
                        );
                        ?>

                        <?= CommentWidget::widget([
                            'attributes' => [
                                'model' => $model::className(),
                                'record_id' => $model->id,
                            ],
                            'showForm' => ($voted || $model->vote == $model::VOTE_NO),
                        ]); ?>

                        <div class="pd-top-45">
                            <a href="<?= Url::to(['index']) ?>" class="btn btn-md btn-grey btn-grey--light">
                                Вернуться к списку
                            </a>
                        </div>
                    </div>
                </div>
                <aside class="main-aside pd-top-55">
                    <div class="border-block pd-aside-block-sm">
                        <ul class="list-date">
                            <li>
                            <span>
                                <?= Yii::$app->getFormatter()->asDate($model->date_begin, 'dd MMMM yyyy') ?> года
                            </span>
                            </li>
                            <?php if ($model->date_begin < $model->date_end): ?>
                                <li>
                                <span>
                                    <?= Yii::$app->getFormatter()->asDate($model->date_end, 'dd MMMM yyyy') ?> года
                                </span>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                    <div class="wrap-aside-fix">
                        <?php if (!$voted): ?>
                            <?= CouncilDiscussionVoteWidget::widget([
                                'model' => $model,
                            ]); ?>
                        <?php endif; ?>
                    </div>
                </aside>
            </div>
        </div>
    </section>

<?= \app\modules\council\widgets\CouncilDiscussionOtherWidget::widget([
    'model' => $model,
]); ?>