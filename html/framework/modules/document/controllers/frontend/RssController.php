<?php

namespace app\modules\document\controllers\frontend;

use app\modules\document\models\Document;
use yii\data\ActiveDataProvider;
use Yii;
use yii\helpers\Url;
use yii\helpers\StringHelper;

class RssController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Document::find()->hidden(),
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
            'pagination' => [
                'pageSize' => 10
            ],
        ]);

        $response = Yii::$app->getResponse();
        $headers = $response->getHeaders();

        $headers->set('Content-Type', 'application/rss+xml; charset=utf-8');

        echo \Zelenin\yii\extensions\Rss\RssView::widget([
            'dataProvider' => $dataProvider,
            'channel' => [
                'title' => function ($widget, \Zelenin\Feed $feed) {
                    $feed->addChannelTitle(Yii::$app->name);
                },
                'link' => Url::to(['/docs'], true),
                'description' => 'RSS лента документов',
                'language' => function ($widget, \Zelenin\Feed $feed) {
                    return Yii::$app->language;
                },

            ],
            'items' => [
                'title' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return $model->title;
                },

                'description' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return StringHelper::truncateWords($model->announce, 50);
                },
                'link' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return Url::to(['/docs', 'url_id' => $model->id], true);
                },

                'guid' => function ($model, $widget, \Zelenin\Feed $feed) {
                    return md5(StringHelper::basename(get_class($model)) . $model->id);
                },
                'pubDate' => function ($model, $widget, \Zelenin\Feed $feed) {
                    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $model->updated_at);
                    return $date->format(DATE_RSS);
                }
            ]
        ]);
    }

}
