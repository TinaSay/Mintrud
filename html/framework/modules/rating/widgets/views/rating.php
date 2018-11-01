<?php

use yii\web\JsExpression;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $rating \app\modules\rating\models\Rating */
/** @var $avgRating integer */
?>

<div class="border-block block-arrow">
    <h3 class="text-black text-center">Оцените материал</h3>
    <!--
        скрипт (ниже на странице инициализация) генерит сюда инпут и спаны,
        по дизайну после того, как голос учтен, кнопка 'голосовать' пропадает
        Можно к форме добавить класс .send, у кнопки появится дисплей нон
     -->

    <form class="rate-form<?= $rating->isNewRecord ? '' : ' send' ?>" action="<?= Url::to('/rating/default/rating') ?>">
        <div class="rate_row"></div>
        <input class="get_module" value="<?= $rating->module ?>" type="hidden">
        <input class="get_record_id" value="<?= $rating->record_id ?>" type="hidden">
        <button class="btn-rate btn btn-block btn-primary">Голосовать</button>
    </form>
</div>

<?php

$js = new JsExpression("
    $('.rate_row').starwarsjs({
        stars: 5,
        default_stars: $avgRating 
    }); 
");
$this->registerJs($js);
?>
