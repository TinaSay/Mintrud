<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 16:56
 */

/* @var $this yii\web\View */
/* @var $model \app\modules\staticVote\models\StaticVoteQuestionnaire */
/* @var $questions \app\modules\staticVote\models\StaticVoteQuestion[] */

\app\modules\staticVote\assets\StaticPollAsset::register($this);

$this->title = $model['title'];

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}
?>
<section class="pd-top-0 pd-bottom-30">
    <div class="container">
        <div class="row">
            <section class="pd-top-0 pd-bottom-30">
                <div class="container">
                    <div class="row">
                        <div class="main col-md-12 questionnaire">
                            <!-- <h1 class="page-title text-black"><?= $this->title ?></h1> -->
                            <?php if ($model['text']): ?>
                                <div class="post-content text-black pd-top-35 pd-bottom-35">
                                    <?= $model['text']; ?>
                                </div>
                            <?php endif; ?>
                            <h3 style="text-align: right;">Проголосовало <?= $model['answers'] ?> чел.</h3>
                            <div class="bg-gray bg-box text-black grey-interview-container">
                                <div class="container-fluid">
                                    <div class="row">
                                        <?= $this->render('_questions', [
                                            'model' => $model,
                                            'questions' => $questions,
                                        ]); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</section>