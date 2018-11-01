<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 04.06.17
 * Time: 18:36
 */

use app\themes\paperDashboard\assets\PaperDashboardAsset;
use app\themes\paperDashboard\assets\ThemifyIconsAsset;
use app\themes\paperDashboard\widgets\menu\DropDownWidget;
use app\themes\paperDashboard\widgets\menu\MenuWidget;
use app\widgets\alert\AlertWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;

/* @var $this yii\web\View */
/* @var $content string */

PaperDashboardAsset::register($this);
ThemifyIconsAsset::register($this);
YiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <title><?= Html::encode($this->title) ?></title>
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport"/>
    <meta name="viewport" content="width=device-width"/>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrapper">
    <div class="sidebar" data-background-color="blue" data-active-color="danger">
        <div class="logo">
            <a href="<?= Url::to(['/']) ?>" class="simple-text logo-mini">
                <?= Html::img('@web/img/logo.svg', ['alt' => 'Лого']) ?>
            </a>
            <a href="<?= Url::to(['/']) ?>" class="simple-text logo-normal">
                АСУ ГИР
            </a>
        </div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <div class="user">
                    <div class="photo"></div>
                    <div class="info">
                        <li>
                            <a data-toggle="collapse" href="#profile" class="collapsed">
                                <p>
                                    <?= ArrayHelper::getValue(Yii::$app->getUser()->getIdentity(), 'login') ?>
                                    <b class="caret"></b>
                                </p>
                            </a>
                            <div class="clearfix"></div>
                            <div class="collapse" id="profile">
                                <ul class="nav">
                                    <li>
                                        <a href="<?= Url::to(['/auth/profile']) ?>">
                                            <p class="sidebar-normal">
                                                Мой профиль
                                            </p>
                                            <i class="ti-angle-right"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="<?= Url::to(['/auth/default/logout']) ?>">
                                            <p class="sidebar-normal">
                                                Выход
                                            </p>
                                            <i class="ti-angle-right"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                    </div>
                </div>
            </ul>

            <?= MenuWidget::widget([
                'items' => ArrayHelper::getValue(Yii::$app->params, ['menu']),
            ]) ?>

        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
                </div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand btn-magnify" target="_blank" href="/"><span class="full-name">Министерство труда и социальной защиты Российской федерации</span>
                        <i class="ti-new-window"></i></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right header__navbar-right">
                        <li class="header-out">
                            <a class="btn-magnify" href="<?= Url::to(['/auth/default/logout']) ?>">
                                <i class="ti-shift-left"></i>
                                <p class="hidden-text-nav">Выход</p>
                            </a>
                        </li>
                    </ul>
                    <?= DropDownWidget::widget([
                        'items' => ArrayHelper::getValue(Yii::$app->params, 'dropdown', []),
                    ]) ?>
                </div>
            </div>
        </nav>

        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <?= AlertWidget::widget() ?>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>

        <footer class="footer">
            <div class="container-fluid">
                <nav class="pull-left">
                    <ul>
                        <li>
                            © 2016–<?= (new DateTime())->format('Y') ?> Автоматизированная система управления
                            государственными Интернет-ресурсами
                        </li>
                    </ul>
                </nav>
            </div>
        </footer>
    </div>
</div>
<?php $this->endBody() ?>

<script>

    $(document).ready(function () {

        /*  **************** Views  - barchart ******************** */
        var dataViews = {
            labels: ['22.07', '23.07', '24.07', '25.07', '26.07', '27.07', '28.07', '29.07', '30.07', '31.07'],
            series: [
                [267, 323, 520, 780, 552, 759, 650, 290, 326, 600]
            ]
        };

        var optionsViews = {
            seriesBarDistance: 10,
            classNames: {
                bar: 'ct-bar'
            },
            axisX: {
                showGrid: false

            },
            height: "250px"

        };

        var responsiveOptionsViews = [
            ['screen and (max-width: 640px)', {
                seriesBarDistance: 5,
                axisX: {
                    labelInterpolationFnc: function (value) {
                        return value[0];
                    }
                }
            }]
        ];

        if ($('#chartViews').length > 0) {
            Chartist.Bar('#chartViews', dataViews, optionsViews, responsiveOptionsViews);
        }

        //  multiple bars chart
        var data = {
            labels: ['02/2017', '03/2017', '04/2017', '05/2017', '06/2017', '07/2017'],
            series: [
                [28, 45, 78, 57, 68, 89],
                [11, 17, 45, 28, 33, 56]
            ]
        };

        var options = {
            seriesBarDistance: 10,
            axisX: {
                showGrid: false
            },
            height: "245px"
        };

        var responsiveOptions = [
            ['screen and (max-width: 640px)', {
                seriesBarDistance: 5,
                axisX: {
                    labelInterpolationFnc: function (value) {
                        return value[0];
                    }
                }
            }]
        ];

        if ($('#chartActivity').length > 0) {
            Chartist.Line('#chartActivity', data, options, responsiveOptions);
        }

        $('#chartDashboard').easyPieChart({
            lineWidth: 6,
            size: 160,
            scaleColor: false,
            trackColor: 'rgba(255,255,255,.25)',
            barColor: '#FFFFFF',
            animate: ({duration: 5000, enabled: true})
        });

        $('.scroll-content').perfectScrollbar();

        $('.btn-warning').click(function () {
            swal({
                title: "Ошибка",
                text: "У вас недостаточно прав для выполнения этой операции.",
                buttonsStyling: false,
                confirmButtonText: 'Ок',
                confirmButtonClass: "btn btn-warning btn-fill"
            });
        });

        $('.btn-warning-2').click(function () {
            swal({
                title: "Ошибка",
                text: "К сожалению, у вас не хватает прав для просмотра данного раздела.",
                buttonsStyling: false,
                confirmButtonText: 'Ок',
                confirmButtonClass: "btn btn-warning btn-fill"
            });
        });

    });

</script>
</body>
</html>
<?php $this->endPage() ?>
