<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.07.2017
 * Time: 16:54
 */

declare(strict_types = 1);

namespace app\modules\directory\rules;


use app\modules\directory\models\Directory;
use app\modules\document\models\DescriptionDirectory;
use Yii;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * Class DescriptionUrlRule
 * @package app\modules\directory\rules
 */
class DescriptionUrlRule extends BaseDirectoryUrlRule
{
    /**
     * @param \yii\web\UrlManager $manager
     * @param \yii\web\Request $request
     * @return array|bool
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

        if (!isset($this->getDescription()[$directory['id']])) {
            return false;
        }
        $descriptionId = $this->getDescription()[$directory['id']];

        $rules = $this->getRouteList();
        $route = ArrayHelper::getValue($rules, $directory['type']);

        if (is_null($route)) {
            return false;
        }

        return [$route, ['directoryId' => $directory['id'], 'descriptionId' => $descriptionId]];
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if (!isset($params['directoryId'])) {
            return false;
        }

        if (!isset($params['descriptionId'])) {
            return false;
        }
        if (!isset($this->map()[$route])) {
            return false;
        }
        return $route;
    }

    /**
     * @return array
     */
    public function getDescription(): array
    {
        static $map;
        if (is_null($map)) {
            $key = [
                __CLASS__,
                __METHOD__,
                __LINE__
            ];

            $dependencies = new TagDependency([
                'tags' => [
                    Directory::class,
                    DescriptionDirectory::class
                ]
            ]);

            if (($map = Yii::$app->cache->get($key)) === false) {
                $map = DescriptionDirectory::find()
                    ->select(['id', 'directory_id'])
                    ->hidden()
                    ->indexBy('directory_id')
                    ->column();

                Yii::$app->cache->set($key, $map, null, $dependencies);
            }
        }
        return $map;
    }


}