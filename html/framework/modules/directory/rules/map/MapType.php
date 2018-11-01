<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.06.2017
 * Time: 17:02
 */

declare(strict_types = 1);


namespace app\modules\directory\rules\map;


use app\modules\directory\models\Directory;
use Yii;
use yii\caching\TagDependency;

/**
 * Class BaseMap
 * @package app\modules\directory\rules\map
 */
class MapType implements MapInterface
{
    /**
     * @var array
     */
    private $types = [];

    /**
     * @var array|null
     */
    public static $map;

    /**
     * MapType constructor.
     * @param array $types
     */
    public function __construct(array $types)
    {
        $this->types = $types;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        $key = [
            __CLASS__,
            __METHOD__,
            $this->types,
        ];
        $dependency = new TagDependency([
            'tags' => [Directory::class],
        ]);
        if (!($map = Yii::$app->cache->get($key))) {
            $map = Directory::find()
                ->select(['url', 'type', 'id'])
                ->inType($this->types)
                ->indexBy('url')
                ->asArray()
                ->all();

            Yii::$app->cache->set($key, $map, null, $dependency);
        }
        return $map;
    }
}