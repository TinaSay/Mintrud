<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 25.07.17
 * Time: 17:21
 */

namespace app\themes\paperDashboard\widgets\analytics;

use krok\analytics\AnalyticsFactory;
use yii\base\Widget;

/**
 * Class AnalyticsWidget
 *
 * @package app\themes\paperDashboard\widgets\analytics
 */
class AnalyticsWidget extends Widget
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $constructor = [];

    /**
     * @var AnalyticsFactory
     */
    private $factory;

    /**
     * AnalyticsWidget constructor.
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
        $receive = $this->factory->create($this->name, $this->constructor)->receive();

        return $this->render($this->name, ['receive' => $receive]);
    }
}
