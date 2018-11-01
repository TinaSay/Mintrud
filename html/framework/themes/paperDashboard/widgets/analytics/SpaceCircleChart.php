<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.08.17
 * Time: 17:40
 */

namespace app\themes\paperDashboard\widgets\analytics;

use krok\analytics\AnalyticsFactory;
use Yii;
use yii\base\Widget;

/**
 * Class SpaceCircleChart
 *
 * @package app\themes\paperDashboard\widgets\analytics
 */
class SpaceCircleChart extends Widget
{
    /**
     * @var AnalyticsFactory
     */
    private $factory;

    /**
     * SpaceCircleChart constructor.
     *
     * @param AnalyticsFactory $factory
     * @param array $config
     */
    public function __construct(AnalyticsFactory $factory, array $config = [])
    {
        $this->factory = $factory;
        parent::__construct($config);
    }

    /**
     * @return string
     */
    public function run()
    {
        $total = $this->factory->create('space/total', [Yii::getAlias('@webroot')])->receive();
        $used = $this->factory->create('space/used', [Yii::getAlias('@webroot')])->receive();

        $percent = $used / $total;

        return $this->render('space/circleChart', [
            'percent' => $percent,
            'total' => $total,
            'used' => $used,
        ]);
    }
}
