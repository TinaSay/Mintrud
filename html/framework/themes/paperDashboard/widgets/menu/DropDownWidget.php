<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 01.04.18
 * Time: 10:40
 */

namespace app\themes\paperDashboard\widgets\menu;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class DropDownWidget
 *
 * @package app\themes\paperDashboard\widgets\menu
 */
class DropDownWidget extends Widget
{
    /**
     * @var array
     */
    public $items = [];

    /**
     * @var string
     */
    public $translateCategory = 'system';

    /**
     * @var int
     */
    protected static $index = 0;

    /**
     * @var string
     */
    protected $route;

    /**
     * @var array
     */
    protected $params;

    public function init()
    {
        parent::init();

        if ($this->route === null && Yii::$app->controller !== null) {
            $this->route = Yii::$app->controller->getRoute();
        }

        if ($this->params === null) {
            $this->params = Yii::$app->request->getQueryParams();
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        return $this->renderItems($this->items, 'nav navbar-nav navbar-right header__navbar-right');
    }

    /**
     * @param array $items
     * @param string|null $class
     *
     * @return string
     */
    protected function renderItems(array $items, string $class = null): string
    {
        $li = [];

        foreach ($items as $item) {
            $child = ArrayHelper::getValue($item, 'items', []);

            if (!$this->checkAccess($item) && !$this->childCheckAccess($child)) {
                continue;
            }

            $ul = $this->renderItems($child, 'dropdown-menu');
            $li[] = $this->renderItem($item, $ul);
        }

        return $li ? Html::tag('ul', implode(PHP_EOL, $li), ['class' => $class]) : '';
    }

    /**
     * @param array $item
     * @param string $ul
     *
     * @return string
     */
    protected function renderItem(array $item, string $ul): string
    {
        self::$index++;

        $label = ArrayHelper::getValue($item, 'label');
        $icon = ArrayHelper::getValue($item, 'icon');
        $url = ArrayHelper::getValue($item, 'url');

        $translate = Yii::t($this->translateCategory, $label);

        $i = $icon ? Html::tag('i', '', ['class' => $icon]) : '';
        $caret = $ul ? Html::tag('b', '', ['class' => 'caret']) : '';
        $options = $ul ? [
            'href' => '#dropdown-' . self::$index,
            'class' => 'btn-magnify dropdown-toggle',
            'data-toggle' => 'dropdown',
        ] : ['href' => Url::to($url)];

        $text = $i . PHP_EOL . ($ul ? Html::tag('p', $translate,
                ['class' => 'hidden-text-nav']) : $translate) . PHP_EOL . $caret;
        $content = Html::a($text, null, $options) . PHP_EOL . $ul;

        return Html::tag('li', $content, $ul ? ['class' => 'dropdown'] : []);
    }
}
