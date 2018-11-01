<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 015 15.10.17
 * Time: 22:58
 */

use yii\helpers\Html;
use yii\helpers\Url;

?>
<h4 class="text-uppercase text-prime pd-bottom-10">RSS-лента документов</h4>
<p class="text-base pd-bottom-20">Все последние обновления документов на сайте Министерства труда и социальной
    защиты.</p>
<button data-toggle="modal" data-target="#modalRss" class="btn btn-block btn-primary">Подписаться</button>

<div id="modalRss" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">RSS лента документов</h4>
            </div>
            <div class="modal-body">
                <p>Скопируйте ссылку в используемую вами программу-агрегатор</p>
                <div class="rss-link">
                    <?= Html::a(Url::to('document/rss', true), Url::to('document/rss', true), ['target' => 'blank']) ?>
                </div>
            </div>
        </div>
    </div>
</div>