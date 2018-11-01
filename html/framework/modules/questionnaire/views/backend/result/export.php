<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 30.06.17
 * Time: 17:13
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/* @var $this yii\web\View */
/** @var $questionnaire \app\modules\questionnaire\models\Questionnaire */
/** @var $results \app\modules\questionnaire\models\Result[] */

$this->title = $questionnaire->title;

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Html::encode($this->title) ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

    <?php $this->head() ?>
    <style>
        html, body {
            padding: 0;
            margin: 0;
        }

        body {
            color: #66615b;
            font-size: 14px;
            font-family: Arial, sans-serif;
            height: 100vh;
        }

        body a,
        body a:hover,
        body a:focus,
        a:active {
            color: #429cb6;
            text-decoration: none;
        }

        .content {
            background-color: #f4f3ef;
            min-height: 100%;
            padding: 40px;
        }

        .card {
            border-radius: 6px;
            box-shadow: 0 2px 2px rgba(204, 197, 185, 0.5);
            background-color: #FFFFFF;
            color: #252422;
            margin-bottom: 20px;
            position: relative;
        }

        .card .card-header {
            padding: 20px 15px 0;
            position: relative;
            border-radius: 3px 3px 0 0;
            z-index: 3;
        }

        .card .card-title {
            margin: 0;
            color: #252422;
            font-weight: 300;
        }

        .card .card-content {
            padding: 15px 15px 10px 15px;
        }

        h4 {
            font-size: 1.5em;
            font-weight: 600;
            line-height: 1.2em;
        }

        .table {
            border-collapse: inherit;
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            background-color: transparent;
        }

        .table th, .table td {
            vertical-align: middle;
            box-sizing: border-box;
        }

        .table tr th,
        .table tr td {
            padding: 12px 11px;
            font-size: 1em;
            font-weight: 300;
        }

        .table tr td {
            border-top: 1px solid #CCC5B9;
        }

        ul {
            list-style: none;
            padding-left: 10px;
        }

        ul li {
            margin-bottom: 6px;
            font-size: 14px;
            width: 100%;
        }

        .collapse-link {
            width: 100%;
        }

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<?php $this->beginBody() ?>
<div class="content">
    <div class="card">

        <div class="card-header">
            <h4 class="card-title"><?= $this->title ?></h4>
        </div>

        <div class="card-content">
            <?php if (!empty($results)): ?>
                <ul>
                    <?php foreach ($results as $result): ?>
                        <li>
                            <a href="#" class="collapse-link">Ответ
                                от <?= Yii::$app->formatter->asDatetime($result->created_at); ?>
                                (IP: <?= long2ip($result->ip); ?>)
                            </a>
                            <div class="hidden collapsible">
                                <?= $this->render('_table', [
                                    'resultAnswers' => ArrayHelper::index($result->resultAnswers, 'id', 'question_id'),
                                    'resultAnswerTexts' => $result->resultAnswerTexts,
                                ]); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Нет данных.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $('.collapse-link').on('click', function (e) {
            e.preventDefault();
            $(this).next('.collapsible').slideToggle();
        });
    })
</script>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>

