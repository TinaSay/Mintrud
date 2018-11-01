<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 05.07.17
 * Time: 14:46
 */

use app\core\helpers\MenuHelper;

/** @var $this \yii\web\View */

?>
<div class="navbar-collapse collapse pull-right" id="navbar-main">
    <ul class="nav navbar-nav">

        <li<?= MenuHelper::isActive('ministry/about|ministry$') ? ' class="active"' : ''; ?>>
            <a title="О Министерстве" href="/ministry">
                Министерство
            </a>
        </li>
        <li<?= MenuHelper::isActive('/ministry/programms') ? ' class="active"' : ''; ?>>
            <a title="Деятельность" href="/ministry/programms">
                Деятельность
            </a>
        </li>
        <li class="li-static<?= MenuHelper::isActive('news/news|events|media|audio|video') ? ' active' : ''; ?>">
            <a title="Пресс-центр" href="/news/news/list">
                Пресс-центр
            </a>
            <!-- подменю -->
           <div class="nav-submenu" style="visibility: hidden;">
              <div class="container">
                <div class="row">
                  <div class="col-md-3">
                        <!-- заголовок списка -->
                      <h3 class="section-head text-black">События</h3>
                      <div class="nav-submenu__content">
                        <!-- новость -->
                        <div class="news-card news-card-daily-map">
                          <div class="news-card-body"><a class="text-black" href="#">
                              <p class="news-date">20 июня 2017</p>
                              <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                        </div>
                        <!-- конец новости -->
                        <div class="news-card news-card-daily-map">
                          <div class="news-card-body"><a class="text-black" href="#">
                              <p class="news-date">20 июня 2017</p>
                              <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                        </div>
                        <!-- ссылка на все новости -->
                        </div><a class="btn btn-block btn-grey" href="#">Все новости</a>

                    </div>
                  <div class="col-md-3">
                    <h3 class="section-head text-black">Мероприятия</h3>
                    <div class="nav-submenu__content">
                      <div class="news-card news-card-daily-map">
                        <div class="news-card-body"><a class="text-black" href="#">
                            <p class="news-date">20 июня 2017</p>
                            <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                      </div>
                      <div class="news-card news-card-daily-map">
                        <div class="news-card-body"><a class="text-black" href="#">
                            <p class="news-date">20 июня 2017</p>
                            <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                      </div>
                    </div><a class="btn btn-block btn-grey" href="#">Все новости</a>
                  </div>
                  <div class="col-md-3">
                    <h3 class="section-head text-black">Медиафайлы</h3>
                    <div class="nav-submenu__content">
                      <div class="news-card news-card-daily-map">
                        <div class="news-card-body"><a class="text-black" href="#">
                            <p class="news-date">20 июня 2017</p>
                            <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                      </div>
                      <div class="news-card news-card-daily-map">
                        <div class="news-card-body"><a class="text-black" href="#">
                            <p class="news-date">20 июня 2017</p>
                            <p class="news-title">Министр Максим Топилин: Качество оказания социальных услуг будет учитываться при оценке эффективности губернаторов</p></a></div>
                      </div>
                    </div><a class="btn btn-block btn-grey" href="#">Все новости</a>
                  </div>
                  <div class="col-md-3">
                    <h3 class="section-head text-black">Пресс-служба</h3>
                    <div class="nav-submenu__content text-black">
                      <div class="row nav-contacts-elem">
                        <div class="col-md-4">Телефон:</div>
                        <div class="col-md-8">+7 (495) 606-18-18</div>
                        <div class="col-md-4"></div>
                        <div class="col-md-8">+7 (495) 606-17-63</div>
                      </div>
                      <div class="row nav-contacts-elem last">
                        <div class="col-md-4">Факс:</div>
                        <div class="col-md-8">+7 (495) 606-18-62</div>
                      </div>
                      <div class="row">
                        <div class="col-md-12 nav-submenu--heading">Электронная почта</div><br/>
                        <div class="col-md-12"><a class="text-black" href="mailto:isyanovams@rosmintrud.ru"><b>isyanovams@rosmintrud.ru</b></a><br/><a class="text-black" href="mailto:ushakovamv@rosmintrud.ru"><b>ushakovamv@rosmintrud.ru</b></a></div>
                      </div>
                    </div><a class="btn btn-block btn-grey" href="#">Все новости</a>
                  </div>
                </div>
              </div>
            </div>

        </li>
        <li<?= MenuHelper::isActive('/docs') ? ' class="active"' : ''; ?>>
            <a title="Документы" href="/docs/">
                Документы
            </a>
        </li>
        <li<?= MenuHelper::isActive('/ministry/services') ? ' class="active"' : ''; ?>>
            <a title="Госуслуги" href="/ministry/services">
                Госуслуги
            </a>
        </li>
        <li<?= MenuHelper::isActive('/ministry/opengov|opendata') ? ' class="active"' : ''; ?>>
            <a title="Открытое министерство" href="/ministry/opengov">
                Открытое министерство
            </a>
        </li>
        <li<?= MenuHelper::isActive('/ministry/contacts') ? ' class="active"' : ''; ?>>
            <a title="Контакты" href="/ministry/contacts">
                Контакты
            </a>
        </li>
    </ul>
</div>
