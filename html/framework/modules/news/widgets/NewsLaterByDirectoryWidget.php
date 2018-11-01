<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 18:30
 */

// declare(strict_types=1);


namespace app\modules\news\widgets;


use app\modules\directory\models\Directory;
use app\modules\news\models\News;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class NewsLaterByDirectoryWidget
 * @package app\modules\news\widgets
 */
class NewsLaterByDirectoryWidget extends Widget
{
    /**
     * @var array|int
     */
    public $directories;
    public $except;
    public $limit = 6;

    /**
     *
     */
    public function init(): void
    {
        $this->directories = (array)$this->directories;
        $this->except = (array)$this->except;
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            Yii::$app->language,
            $this->directories,
            $this->except
        ];

        $dependency = new TagDependency([
            'tags' => [
                News::class,
                Directory::class,
            ]
        ]);


        if (!($models = Yii::$app->cache->get($key))) {
            $models = News::find()
                ->inDirectory($this->directories)
                ->inNotNews($this->except)
                ->innerJoinWith(['directory'])
                ->language()
                ->directoryHidden()
                ->hidden()
                ->orderByDate()
                ->limit($this->limit)
                ->all();

            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $this->render('news/list', ['models' => $models]);
    }

}