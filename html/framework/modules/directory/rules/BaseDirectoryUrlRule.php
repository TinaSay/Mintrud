<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 16:57
 */

declare(strict_types = 1);


namespace app\modules\directory\rules;

use app\modules\directory\rules\map\MapInterface;
use app\modules\directory\rules\map\MapType;
use app\modules\directory\rules\type\BaseUrlDirectory;
use yii\base\InvalidConfigException;
use yii\web\UrlRule;

/**
 * Class BaseDirectoryUrlRule
 * @package app\modules\directory\rules
 */
abstract class BaseDirectoryUrlRule extends UrlRule
{

    /**
     * @var string
     */
    public $route = '';

    /**
     * @var BaseUrlDirectory[]
     */
    public $routes = [];

    /**
     * @var MapInterface
     */
    protected $map;


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        $types = [];

        foreach ($this->routes as $index => $route) {
            $this->routes[$index] = new $route();
            if ($this->routes[$index] instanceof BaseUrlDirectory) {
                $types[] = $this->routes[$index]->getType();
            } else {
                throw new InvalidConfigException('Invalid config for DirectoryUrlRule::routes');
            }
        }

        $this->map = new MapType($types);
        if (!($this->map instanceof MapInterface)) {
            throw new InvalidConfigException(static::className() . '::map must be set as ' . MapInterface::class);
        }
        parent::init();
    }


    /**
     * @return array
     * @throws InvalidConfigException
     */
    protected function getRouteList(): array
    {
        $list = [];
        foreach ($this->routes as $route) {
            $list[$route->getType()] = ltrim($route->getRoute(), '/');
        }
        return $list;
    }

    /**
     * @return array
     */
    protected function map(): array
    {
        static $map;
        if (is_null($map)) {
            return $map = $this->map->get();
        }
        return $map;
    }
}