<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 17:24
 */

// declare(strict_types=1);


namespace app\modules\directory\widgets;


use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class CategoryNewsWidget
 * @package app\modules\directory\widgets
 */
class CategoryNewsWidget extends Widget
{
    /** @var int */
    public $active;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (!is_integer($this->active)) {
            throw new InvalidConfigException(static::class . '::active must be as integer');
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language
        ];

        $dependency = new TagDependency([
            'tags' => Directory::class,
        ]);

        if (!($models = Yii::$app->cache->get($key)) || YII_ENV_DEV) {
            $models = Directory::find()
                ->hidden()
                ->type(TypeInterface::TYPE_NEWS)
                ->parent()
                ->language()
                ->orderBy(['depth' => SORT_ASC, 'position' => SORT_ASC])
                ->all();

            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $this->render('news/category', ['models' => $models]);
    }

    /**
     * @return int
     */
    public function getActive(): int
    {
        return $this->active;
    }
}