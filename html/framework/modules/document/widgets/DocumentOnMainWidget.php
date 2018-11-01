<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.07.2017
 * Time: 13:45
 */

declare(strict_types = 1);

namespace app\modules\document\widgets;

use app\modules\document\models\WidgetOnMain;
use Yii;
use yii\base\Widget;
use yii\caching\TagDependency;

/**
 * Class DocumentOnMainWidget
 * @package app\modules\document\widgets
 */
class DocumentOnMainWidget extends Widget
{
    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__
        ];

        $dependency = new TagDependency([
            'tags' => [
                WidgetOnMain::class
            ]
        ]);

        if (!($tabs = Yii::$app->cache->get($key))) {
            $tabs = WidgetOnMain::find()
                ->innerJoinWith(['type'])
                ->typeHidden()
                ->orderByPosition()
                ->hidden()
                ->all();

            Yii::$app->cache->set($key, $tabs, null, $dependency);
        }

        
        return $this->render('main/tab', ['tabs' => $tabs]);
    }
}