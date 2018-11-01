<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:08
 */
declare(strict_types = 1);


namespace app\modules\directory\rules;

use yii\helpers\ArrayHelper;

/**
 * Class DirectoryUrlRule
 * @package app\modules\directory\rules
 */
class DirectoryUrlRule extends BaseDirectoryUrlRule
{
    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        if (!preg_match($this->pattern, $request->getPathInfo(), $matches)) {
            return false;
        }
        $matches = $this->substitutePlaceholderNames($matches);

        $directory = ArrayHelper::getValue($this->map(), $matches['url']);
        if (is_null($directory)) {
            return false;
        }

        $rules = $this->getRouteList();
        $route = ArrayHelper::getValue($rules, $directory['type']);
        if (is_null($route)) {
            return false;
        }

        return [$route, ['directoryId' => $directory['id']]];
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        ArrayHelper::remove($params, 'language');
        if (!empty($params)) {
            return false;
        }
        if (!isset($this->map()[$route])) {
            return false;
        }

        return $route;
    }
}