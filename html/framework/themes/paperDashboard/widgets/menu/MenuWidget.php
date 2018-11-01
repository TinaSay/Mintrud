<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 06.06.17
 * Time: 14:44
 */

namespace app\themes\paperDashboard\widgets\menu;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class MenuWidget
 *
 * @package app\themes\paperDashboard\widgets\menu
 */
class MenuWidget extends Widget
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
        return $this->renderItems($this->items);
    }

    /**
     * @param array $items
     *
     * @return string
     */
    protected function renderItems(array $items): string
    {
        $li = [];

        foreach ($items as $item) {
            $child = ArrayHelper::getValue($item, 'items', []);

            if (!$this->checkAccess($item) && !$this->childCheckAccess($child)) {
                continue;
            }

            $ul = $this->renderItems($child);
            $li[] = $this->renderItem($item, $ul);
        }

        return $li ? Html::tag('ul', implode(PHP_EOL, $li), ['class' => 'nav']) : '';
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
        $icon = ArrayHelper::getValue($item, 'icon', 'ti-angle-right');
        $url = ArrayHelper::getValue($item, 'url');

        $translate = Yii::t($this->translateCategory, $label);

        $active = $this->isItemActive($item);

        $caret = $ul ? Html::tag('b', '', ['class' => 'caret']) : '';
        $options = $ul ? ['href' => '#menu-' . self::$index, 'data-toggle' => 'collapse'] : ['href' => Url::to($url)];
        $collapse = $ul ? Html::tag('div', $ul,
            ['class' => $active ? 'collapse in' : 'collapse', 'id' => 'menu-' . self::$index]) : '';

        $text = Html::tag('i', '', ['class' => $icon]) . Html::tag('p', $translate . $caret);
        $content = Html::a($text, null, $options) . $collapse;

        return Html::tag('li', $content, $active ? ['class' => 'active'] : []);
    }

    /**
     * @param array $item
     *
     * @return bool
     */
    protected function isItemActive(array $item): bool
    {
        if (isset($item['items']) && is_array($item['items'])) {
            foreach ($item['items'] as $item) {
                if ($this->isItemActive($item)) {
                    return true;
                }
            }
        } else {
            $url = ArrayHelper::getValue($item, 'url');
            $route = array_shift($url);
            $route = trim($route, '/');
            $matchParams = true;

            if ($url) {
                foreach ($url as $param => $value) {
                    if (Yii::$app->request->get($param) != $value) {
                        $matchParams = false;
                        break;
                    }
                }
            }

            if (count(explode('/', $route)) > 2) {
                return $route === $this->route && $matchParams;
            }
            if (fnmatch($route . '/*', $this->route)) {
                return $matchParams;
            }

        }

        return false;
    }
}
