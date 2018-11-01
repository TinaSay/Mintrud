<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 22.08.17
 * Time: 14:24
 */

/** @var $this \yii\web\View */
/** @var $questions \app\modules\questionnaire\models\Question[] */
/** @var $questionnaire \app\modules\questionnaire\models\Questionnaire */

$this->title = 'Результаты опроса "' . $questionnaire->title . '"';
if ($questionnaire->name == 'survey_citizens') {
    $this->params['breadcrumbs'][] = ['label' => 'Независимая система оценки качества', 'url' => ['/nsok']];
    $this->params['breadcrumbs'][] = ['label' => $questionnaire->title, 'url' => ['/nsok/' . $questionnaire->name]];
}

$this->params['breadcrumbs'][] = ['label' => 'Результаты опроса'];
?>

<section class="pd-top-40 pd-bottom-30">
    <div class="container">
        <div class="row">
            <div class="main col-md-12">
                <h1 class="page-title text-black"><?= $this->title ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="pd-top-0 pd-bottom-70">
    <div class="container">
        <div class="border-top"></div>

        <?php if (Yii::$app->request->get('status') == 'ok'): ?>
            <div class="alert alert-success">
                <p>Спасибо за то, что приняли участие в нашем опросе.</p>
            </div>
        <?php endif; ?>

        <div class="questionnaire-result">
            <?php foreach ($questions as $question): ?>
                <h3 class="hh4" data-toggle="collapse"
                    data-target="#a-collapse-<?= $question['id']; ?>"><?= $question['title']; ?></h3>
                <div id="a-collapse-<?= $question['id']; ?>" class="questionnaire-answers-stat collapse in">
                    <?php foreach ($question['answers'] as $answer):
                        $total = $question['results'] > 0 ? $question['results'] : 1;
                        $count = $answer['results'];
                        $percent = round($count / $total * 100);
                        ?>
                        <div class="row">
                            <div class="col-lg-5">
                                <span class="text"><?= $answer['title']; ?>
                                    <em class="comment">(<?= $count ?> чел.)</em>
                                </span>
                            </div>
                            <div class="col-lg-7">
                                <div class="percent-box">
                                    <span class="percent"
                                          style="width: <?= $percent ?>%;">
                                        <?php if ($percent > 90): ?><?= $percent; ?>%<?php endif; ?>
                                    </span>
                                    <?php if ($percent <= 90): ?>
                                        <span class="count">
                                            <?= $percent ?>%
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
