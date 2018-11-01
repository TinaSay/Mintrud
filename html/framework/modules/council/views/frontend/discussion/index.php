<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 12:12
 */

/* @var $this yii\web\View */
/* @var $list \app\modules\council\models\CouncilDiscussion[] */
/* @var $pagination \yii\data\Pagination */

use app\modules\comment\widgets\LastDiscussionComment;
use app\modules\council\models\CouncilDiscussion;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode('Общественные обсуждения');

$this->params['breadcrumbs'][] = $this->title;
?>
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <?= $this->render('//parts/breadcrumbs'); ?>

                <h1 class="page-title text-black"><?= $this->title; ?></h1>
                <?php if ($list): ?>
                    <div class="pd-top-10">
                        <?php foreach ($list as $row): ?>
                            <div class="post-list">
                                <p class="page-date text-light">
                                    <?= Yii::$app->getFormatter()->asDate($row['date_begin'], 'dd MMMM yyyy') ?> года
                                    <?php if ($row['date_begin'] < $row['date_end']): ?>
                                        &ndash; <?= Yii::$app->getFormatter()->asDate($row['date_end'], 'dd MMMM yyyy') ?> года
                                    <?php endif; ?></p>
                                <a class="post-name text-black" href="<?= Url::to(['card', 'id' => $row['id']]); ?>">
                                    <?= $row['title']; ?>
                                </a>
                                <ul class="list-info-theme">
                                    <?php if ($row['vote'] == CouncilDiscussion::VOTE_YES): ?>
                                        <li><a class="list-info-theme__plus" href="#"><i></i><?= $row['votes_up']; ?>
                                            </a>
                                        </li>
                                        <li><a class="list-info-theme__minus" href="#"><i></i><?= $row['votes_down']; ?>
                                            </a>
                                        </li>
                                        <li><a class="list-info-theme__neutrally"
                                               href="#"><i></i><?= $row['votes_neutral']; ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="pull-right">
                                        <a class="list-info-theme__comment" href="#"><i></i>Комментариев:
                                            <strong><?= $row['comments']; ?></strong></a>
                                    </li>
                                </ul>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?= $this->render('//parts/pagination', ['pagination' => $pagination]); ?>
                <?php else: ?>
                    <p>К сожалению, в настоящий момент нет активных общественных обсуждений</p>
                <?php endif; ?>

            </div>
            <aside class="main-aside pd-top-55">
                <ul class="list-nav no-mr-top">
                    <li<?php if (Yii::$app->controller->action->id == 'index'): ?> class="active"<?php endif; ?>>
                        <a class="text-black" href="<?= Url::to(['index']); ?>">Список</a>
                    </li>
                    <li<?php if (Yii::$app->controller->action->id == 'calendar'): ?> class="active"<?php endif; ?>>
                        <a class="text-black" href="<?= Url::to(['calendar']); ?>">Календарь</a>
                    </li>
                </ul>
                <?= LastDiscussionComment::widget(); ?>
            </aside>
        </div>
    </div>
</section>