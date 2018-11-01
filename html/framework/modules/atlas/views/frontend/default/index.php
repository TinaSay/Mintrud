<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.07.17
 * Time: 11:04
 */

/* @var $this yii\web\View */

/* @var $rates \app\modules\atlas\models\AtlasDirectoryRate[] */

use app\modules\atlas\assets\AtlasAsset;
use yii\helpers\Url;

AtlasAsset::register($this);

$this->title = 'Демографический атлас';

$this->params['breadcrumbs'][] = ['label' => $this->title];

$this->registerJs(new \yii\web\JsExpression('
$("#map").atlas(window.map.paths, {
    url: "' . Url::to(['get-layer']) . '",
    initRateCode: "growth"    
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
        <div class="story result-vote-box cl">
            <div class="map-box">
                <div class="wrapper">
                    <div id="type-selector">
                        <ul class="ib rate-type-selector">
                            <?php foreach ($rates as $rate): ?>
                                <li<?php if ($rate['code'] == 'growth'): ?> class="active"<?php endif; ?>
                                        data-id="<?= $rate['id'] ?>"
                                        data-rate="<?= $rate['code'] ?>">
                                    <em><?= $rate['title']; ?></em></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="source">По данным Росстата</p>
                        <ul class="sub-type-selector"></ul>
                    </div>
                    <div id="map"></div>
                    <div class="region-info over" id="legends_block">
                        <div class="legend" id="legend_born">
                            <ul class="ib">
                                <li class="addition">
                                    <h4>Число родившихся (1 на 1000 человек)</h4>
                                    <p><em class="addition1"></em>
                                        <span data-cmp="0:4">&lt;2</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="4:8">4-8</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="8:12">8-12</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="12:16">12-16</span>

                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="16:20">16-20</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="20:24">20-24</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="24:">&gt;24</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_died">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Число умерших (1 на 1000 человек)</h4>
                                    <p><em class="decline1"></em>
                                        <span data-cmp="0:4">&lt;2</span>
                                    </p>
                                    <p><em class="decline2"></em>
                                        <span data-cmp="4:8">4-8</span>
                                    </p>
                                    <p><em class="decline3"></em>
                                        <span data-cmp="8:12">8-12</span>
                                    </p>
                                    <p><em class="decline4"></em>
                                        <span data-cmp="12:16">12-16</span>
                                    </p>
                                    <p><em class="decline5"></em>
                                        <span data-cmp="16:20">16-20</span>
                                    </p>
                                    <p><em class="decline6"></em>
                                        <span data-cmp="20:24">20-24</span>
                                    </p>
                                    <p><em class="decline7"></em>
                                        <span data-cmp="24:">&gt;24</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_growth">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Сокращение (1 на 1000 человек)</h4>
                                    <p class="null"><em></em>
                                        <span data-cmp="0">0</span>
                                    </p>
                                    <p><em class="decline7"></em>
                                        <span data-cmp=":-24">&gt;24</span>
                                    </p>
                                    <p><em class="decline6"></em>
                                        <span data-cmp="-24:-20">20-24</span>
                                    </p>
                                    <p><em class="decline5"></em>
                                        <span data-cmp="-20:-16">16-20</span>
                                    </p>
                                    <p><em class="decline4"></em>
                                        <span data-cmp="-16:-12">12-16</span>
                                    </p>
                                    <p><em class="decline3"></em>
                                        <span data-cmp="-12:-8">8-12</span>
                                    </p>
                                    <p><em class="decline2"></em>
                                        <span data-cmp="-8:-4">4-8</span>
                                    </p>
                                    <p><em class="decline1"></em>
                                        <span data-cmp="-4:0">&lt;4</span>
                                    </p>
                                </li>
                                <li class="addition">
                                    <h4>Прирост (1 на 1000 человек)
                                    </h4>
                                    <p class="null"><em></em>
                                        <span data-cmp="0">0</span>
                                    </p>
                                    <p><em class="addition1"></em>
                                        <span data-cmp="0:4">&lt;2</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="4:8">4-8</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="8:12">8-12</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="12:16">12-16</span>
                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="16:20">16-20</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="20:24">20-24</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="24:">&gt;24</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_infant">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Младенческая смертность (1 на 1000 человек)</h4>
                                    <p><em class="decline1"></em>
                                        <span data-cmp="0:4">&lt;2</span>
                                    </p>
                                    <p><em class="decline2"></em>
                                        <span data-cmp="4:8">4-8</span>
                                    </p>
                                    <p><em class="decline3"></em>
                                        <span data-cmp="8:12">8-12</span>
                                    </p>
                                    <p><em class="decline4"></em>
                                        <span data-cmp="12:16">12-16</span>
                                    </p>
                                    <p><em class="decline5"></em>
                                        <span data-cmp="16:20">16-20</span>
                                    </p>
                                    <p><em class="decline6"></em>
                                        <span data-cmp="20:24">20-24</span>
                                    </p>
                                    <p><em class="decline7"></em>
                                        <span data-cmp="24:">&gt;24</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_babies">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Суммарный коэффициент рождаемости</h4>
                                    <p><em class="addition1"></em>
                                        <span data-cmp=":1.2">&lt;1.2</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="1.2:1.5">1.2-1.5</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="1.5:1.8">1.5-1.8</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="1.8:2.3">1.8-2.3</span>
                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="2.3:2.6">2.3-2.6</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="2.6:2.9">2.6-2.9</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="2.9:">&gt;2.9</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_life">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Ожидаемая продолжительность жизни (число лет)</h4>
                                    <p><em class="addition1"></em>
                                        <span data-cmp=":62">&lt;62</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="62:65">62-65</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="65:67">65-67</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="67:69">67-69</span>
                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="69:71">69-71</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="71:75">71-75</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="75:">&gt;75</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_life_man">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Ожидаемая продолжительность мужчин (число лет)</h4>
                                    <p><em class="addition1"></em>
                                        <span data-cmp=":62">&lt;62</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="62:65">62-65</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="65:67">65-67</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="67:69">67-69</span>
                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="69:71">69-71</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="71:75">71-75</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="75:">&gt;75</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                        <div class="legend" id="legend_life_woman">
                            <ul class="ib">
                                <li class="decline">
                                    <h4>Ожидаемая продолжительность жизни женщин (число лет)</h4>
                                    <p><em class="addition1"></em>
                                        <span data-cmp=":62">&lt;62</span>
                                    </p>
                                    <p><em class="addition2"></em>
                                        <span data-cmp="62:65">62-65</span>
                                    </p>
                                    <p><em class="addition3"></em>
                                        <span data-cmp="65:67">65-67</span>
                                    </p>
                                    <p><em class="addition4"></em>
                                        <span data-cmp="67:69">67-69</span>
                                    </p>
                                    <p><em class="addition5"></em>
                                        <span data-cmp="69:71">69-71</span>
                                    </p>
                                    <p><em class="addition6"></em>
                                        <span data-cmp="71:75">71-75</span>
                                    </p>
                                    <p><em class="addition7"></em>
                                        <span data-cmp="75:">&gt;75</span>
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="story" class="text-black atlas-wrap" data-url="<?= Url::to(['get-region-data']); ?>"></div>
            </div>
        </div>
    </div>
</div>


<script id="regionRatePopupTemplate" type="text/template">
    <div class="point gradient text-black">
        <h2>
            <span class="marker" style="background: <%= color %>"></span>
            <span class="tts_region"><%= region %></span>
        </h2>

        <% if (rates.length == 1 && Object.keys(year_list).length == 1){ %>
        <div class="table-box">
            <table>
                <tr class="table-row">
                    <td class="digit"><%- rates[0]['value'][Object.keys(year_list)[0]] %></td>
                    <td class="rate-title"><%- rate_title %> в <%- year_list[Object.keys(year_list)[0]] %></td>
                </tr>
            </table>
        </div>
        <% }else{ %>
        <h3 class="rate-title"><%= rate_title %></h3>
        <table>
            <tr class="table-head">
                <% if(rates.length > 1){ %>
                <th>&nbsp;</th>
                <% } %>
                <% for(var y in year_list){ %>
                <th class="year"><%- year_list[y] %></th>
                <% } %>
            </tr>
            <% for(var i in rates){ %>
            <tr class="table-row value">
                <% if(rates.length > 1){ %>
                <th class="rate-title title"><%- rates[i].title %></th>
                <% } %>
                <% for(var y in year_list){ %>
                <td class="digit"><%- rates[i]['value'][y] %></td>
                <% } %>
            </tr>
            <% } %>
        </table>
        <% } %>
    </div>
</script>
