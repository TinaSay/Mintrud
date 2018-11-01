<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.07.17
 * Time: 11:04
 */

/* @var $this yii\web\View */

/* @var $rates \app\modules\atlas\models\AtlasDirectoryRate[] */

use app\modules\atlas\assets\AtlasAllowanceAsset;
use yii\helpers\Url;

AtlasAllowanceAsset::register($this);

$this->title = 'Социальный навигатор';

$this->params['breadcrumbs'][] = ['label' => $this->title];

$this->registerJs(new \yii\web\JsExpression('
$("#map-allowance").allowance(window.map.paths, {
    url: "' . Url::to(['get-layer']) . '",
    initRateCode: "family"    
});
'));

?>
<div class="row">
    <div class="main col-md-12 pd-bottom-60">
        <h1 class="page-title text-black"><?= $this->title ?></h1>
        <div id="loader" style="display:none;">
            <div class="loader" id="l_image"></div>
            <p id="l_text">Загрузка данных</p>
        </div>
        <div class="atlas-wrap issue result-vote-box cl">
            <div class="map-box">
                <div class="wrapper">
                    <div id="type-selector">
                        <ul class="ib rate-type-selector">
                            <?php foreach ($rates as $rate): ?>
                                <li<?php if ($rate['code'] == 'family'): ?> class="active"<?php endif; ?>
                                        data-id="<?= $rate['id'] ?>"
                                        data-rate="<?= $rate['code'] ?>">
                                    <em><?= $rate['title']; ?></em></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <div id="map-allowance"></div>
                </div>

                <div id="story" class="text-black atlas-wrap" data-url="<?= Url::to(['get-region-data']); ?>"></div>
            </div>
        </div>
    </div>
</div>


<script id="regionRatePopupTemplate" type="text/template">
    <div class="point gradient text-black">
        <h2 class="no-bottom-border">
            <span class="marker" style="background: <%= color %>"></span>
            <span class="tts_region"><%= region %></span>
        </h2>
    </div>
</script>
