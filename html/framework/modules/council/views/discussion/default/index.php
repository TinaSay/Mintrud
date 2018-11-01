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
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?= $this->render('//parts/pagination', ['pagination' => $pagination]); ?>
                <?php else: ?>
                    <p>К сожалению, в настоящий момент нет активных общественных обсуждений</p>
                <?php endif; ?>

            </div>
            <aside class="main-aside pd-top-55" style="display: none;">
                <ul class="list-nav hidden-xs hidden-sm no-mr-top">
                    <li<?php if (Yii::$app->controller->action->id == 'index'): ?> class="active"<?php endif; ?>>
                        <a class="text-black" href="<?= Url::to(['index']); ?>">Список</a>
                    </li>
                    <li<?php if (Yii::$app->controller->action->id == 'calendar'): ?> class="active"<?php endif; ?>>
                        <a class="text-black" href="<?= Url::to(['calendar']); ?>">Календарь</a>
                    </li>
                </ul>
            </aside>
            <aside class="main-aside pd-top-55">
              <ul class="list-nav no-mr-top">
                <li><a class="text-black" href="/sovet/base">О Совете</a></li>
                <li><a class="text-black" href="/sovet/members">Состав Совета</a></li>
                <li><a class="text-black" href="/sovet/structure">Структура Совета</a></li>
                <li><a class="text-black" href="/sovet/news">Деятельность Совета</a></li>
                <li><a class="text-black" href="/sovet/meetings">Материалы заседаний Совета</a></li>
                <li><a class="text-black" href="/sovet/documents">Документы</a></li>
                <li><a class="text-black" href="/sovet/flashback">История формирования Совета</a></li>
                <li><a class="text-black" href="/sovet/interaction">Взаимодействие с Минтрудом России</a></li>
                <li><a class="text-black" href="/sovet/plan" class="">План работы</a></li>
                <li><a class="text-black" href="/sovet/ethics" class="">Кодекс этики членов общественного совета при Министерстве труда и социальной защиты Российской Федерации</a></li>
                <li style="display: none;"><a class="text-black" href="/sovet/form_sovet/" class="cloud-btn">ваш вопрос<br>или предложение</a></li>
              </ul>
             </aside>            
        </div>
    </div>
</section><br><br>