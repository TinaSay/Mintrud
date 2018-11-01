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
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Html::encode('Календарь общественных обсуждений');

$this->params['breadcrumbs'][] = $this->title;
\app\assets\FullCalendarAsset::register($this);
\app\modules\council\assets\CouncilCalendarAsset::register($this);

?>
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="clearfix">
            <div class="main">
                <?= $this->render('//parts/breadcrumbs'); ?>

                <h1 class="page-title text-black"><?= $this->title; ?></h1>

                <div class="pd-top-10">
                    <div data-url="<?= Url::to(['/discussion/default/calendar']); ?>" class="calendar-container"></div>
                </div>
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