<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.06.2017
 * Time: 17:35
 */

declare(strict_types = 1);


namespace app\modules\news\widgets;


use app\modules\directory\models\Directory;
use app\modules\news\models\WidgetOnMain;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class OnMainListWidget
 * @package app\modules\news\widgets
 */
class NewsOnMainListWidget extends Widget
{
    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                WidgetOnMain::class,
                Directory::class,
            ]
        ]);

        if (!($widgets = Yii::$app->cache->get($key))) {
            $widgets = WidgetOnMain::find()
                ->innerJoinWith(['directory'])
                ->language()
                ->directoryHidden()
                ->hidden()
                ->orderByPosition()
                ->all();

            Yii::$app->cache->set($key, $widgets, null, $dependency);
        }

        return $this->render('main/list', ['widgets' => $widgets]);
    }
}