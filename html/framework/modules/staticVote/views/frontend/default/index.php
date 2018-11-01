<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 29.06.17
 * Time: 16:56
 */

use app\modules\staticVote\assets\StaticVoteAsset;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model \app\modules\staticVote\models\StaticVoteQuestionnaire */
/* @var $questions \app\modules\staticVote\models\StaticVoteQuestion[] */

StaticVoteAsset::register($this);

$this->title = $model['title'];

if (class_exists('yii\debug\Module')) {
    $this->off(\yii\web\View::EVENT_END_BODY, [\yii\debug\Module::getInstance(), 'renderToolbar']);
}

$this->beginPage();
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Html::encode($this->title) ?>"/>
        <title><?= Html::encode($this->title) ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

        <?php $this->head() ?>

    </head>
    <body>
    <?php $this->beginBody() ?>
    <div class="top-content story-box">
        <div class="story">
            <div class="issue">
                <?php if ($model['text']): ?>
                    <div class="shortstory"><?= $model['text']; ?></div>
                <?php endif; ?>
                <?= $this->render('_questions', [
                    'model' => $model,
                    'questions' => $questions,
                ]); ?>
            </div>
        </div>
    </div>

    <?php $this->endBody(); ?>
    </body>
    </html>
<?php $this->endPage(); ?>