<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 11.10.17
 * Time: 13:10
 */

namespace app\modules\news\commands;


use app\modules\directory\models\Directory;
use app\modules\document\models\Direction;
use app\modules\document\models\NewsDirection;
use app\modules\news\models\News;
use yii\console\Controller;


class DirectionController extends Controller
{
    public function actionUpdate()
    {
        ini_set('display_errors', E_ALL);
        ini_set('track_errors', 'on');
        ini_set('memory_limit', '300M');
        echo 'start' . PHP_EOL;
        $news = News::find()->innerJoin(
            Directory::tableName(),
            Directory::tableName() . '.[[id]] = ' . News::tableName() . '.[[directory_id]]'
        )->language('ru-RU')->all();
        foreach ($news as $item) {
            $url = $item->directory->url;
            $direction = Direction::find()->innerJoin(
                Directory::tableName(),
                Directory::tableName() . '.[[id]] = ' . Direction::tableName() . '.[[directory_id]]'
            )
                ->andWhere([Directory::tableName() . '.[[url]]' => $url])
                ->one();
            if (is_null($direction)) {
                echo $item->directory->url . '/' . $item->url_id . PHP_EOL;
                continue;
            }
            $newsDirection = NewsDirection::find()
                ->direction($direction->id)
                ->news($item->id)
                ->one();
            if (!is_null($newsDirection)) {
                continue;
            }
            $did = \Yii::$app->db->createCommand()
                ->insert(
                    NewsDirection::tableName(),
                    [
                        'news_id' => $item->id,
                        'direction_id' => $direction->id,
                    ]
                )->execute();
            if ($did != 1) {
                throw new \RuntimeException('not save');
            }
        }
        echo 'end' . PHP_EOL;
    }
}