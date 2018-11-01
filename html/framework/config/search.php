<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 07.08.17
 * Time: 17:22
 */

return [

    /**
     * News
     */
    [
        'dataProvider' => function () {
            return \app\modules\news\models\News::find()
                ->select([
                    \app\modules\news\models\News::tableName() . '.[[id]]',
                    \app\modules\news\models\News::tableName() . '.[[title]]',
                    \app\modules\news\models\News::tableName() . '.[[text]]',
                    \app\modules\news\models\News::tableName() . '.[[url_id]]',
                    \app\modules\news\models\News::tableName() . '.[[directory_id]]',
                    \app\modules\news\models\News::tableName() . '.[[created_at]]',
                    \app\modules\news\models\News::tableName() . '.[[date]]',
                    \app\modules\directory\models\Directory::tableName() . '.[[url]]',
                ])->where([
                    \app\modules\news\models\News::tableName() . '.[[hidden]]' => \app\modules\news\models\News::HIDDEN_NO,
                ])->innerJoinWith('directory')
                ->groupBy([
                    \app\modules\news\models\News::tableName() . '.[[id]]',
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'News';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function ($model) {
                return $model['date'] ? $model['date'] : $model['created_at'];
            },
            'updated_at' => function () {
                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to([
                    '/' . \yii\helpers\ArrayHelper::getValue($model['directory'], 'url'),
                    'url_id' => $model['url_id'],
                ]);
            },
        ],
    ],
    /**
     * Document
     */
    [
        'dataProvider' => function () {
            return \app\modules\document\models\Document::find()
                ->select([
                    \app\modules\document\models\Document::tableName() . '.[[id]]',
                    \app\modules\document\models\Document::tableName() . '.[[title]]',
                    \app\modules\document\models\Document::tableName() . '.[[announce]]',
                    \app\modules\document\models\Document::tableName() . '.[[text]]',
                    \app\modules\document\models\Document::tableName() . '.[[ministry_number]]',
                    \app\modules\document\models\Document::tableName() . '.[[directory_id]]',
                    \app\modules\document\models\Document::tableName() . '.[[url_id]]',
                    \app\modules\document\models\Document::tableName() . '.[[date]]',
                    \app\modules\directory\models\Directory::tableName() . '.[[url]]',
                ])->where([
                    \app\modules\document\models\Document::tableName() . '.[[hidden]]' => \app\modules\document\models\Document::HIDDEN_NO,
                ])->innerJoinWith('directory')
                ->groupBy([
                    \app\modules\document\models\Document::tableName() . '.[[id]]',
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'Document';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function ($model) {
                return $model['date'];
            },
            'updated_at' => function () {
                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function ($model) {
                return implode(' ', [
                    $model['announce'],
                ]);
            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['announce'],
                    $model['ministry_number'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to([
                    '/' . \yii\helpers\ArrayHelper::getValue($model['directory'], 'url'),
                    'url_id' => $model['url_id'],
                ]);
            },
        ],
    ],
    /**
     * Event
     */
    [
        'dataProvider' => function () {
            return \app\modules\event\models\Event::find()
                ->select([
                    \app\modules\event\models\Event::tableName() . '.[[id]]',
                    \app\modules\event\models\Event::tableName() . '.[[title]]',
                    \app\modules\event\models\Event::tableName() . '.[[place]]',
                    \app\modules\event\models\Event::tableName() . '.[[text]]',
                    \app\modules\event\models\Event::tableName() . '.[[date]]',
                    \app\modules\event\models\Event::tableName() . '.[[begin_date]]',
                    \app\modules\event\models\Event::tableName() . '.[[finish_date]]',
                ])->where([
                    \app\modules\event\models\Event::tableName() . '.[[hidden]]' => \app\modules\event\models\Event::HIDDEN_NO,
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'Events';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function ($model) {
                if ($model['begin_date']) {
                    $dt = DateTime::createFromFormat('Y-m-d', $model['begin_date']);

                    return $dt ? $dt->format('Y-m-d') : null;
                }

                return $model['date'];
            },
            'updated_at' => function ($model) {
                if ($model['finish_date']) {
                    $dt = DateTime::createFromFormat('Y-m-d', $model['finish_date']);

                    return $dt ? $dt->format('Y-m-d') : null;
                }

                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function () {
                return null;
            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['place'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to(['/events/event/view', 'id' => $model['id']]);
            },
        ],
    ],
    /**
     * Media - Audio
     */
    [
        'dataProvider' => function () {
            return \app\modules\media\models\Audio::find()
                ->select([
                    \app\modules\media\models\Audio::tableName() . '.[[id]]',
                    \app\modules\media\models\Audio::tableName() . '.[[title]]',
                    \app\modules\media\models\Audio::tableName() . '.[[text]]',
                    \app\modules\media\models\Audio::tableName() . '.[[created_at]]',
                ])->where([
                    \app\modules\media\models\Audio::tableName() . '.[[hidden]]' => \app\modules\media\models\Audio::HIDDEN_NO,
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'Audio';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function () {
                return null;
            },
            'updated_at' => function () {
                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function () {
                return '';
            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to(['/media/audio/view', 'id' => $model['id']]);
            },
        ],
    ],
    /**
     * Media - Video
     */
    [
        'dataProvider' => function () {
            return \app\modules\media\models\Video::find()
                ->select([
                    \app\modules\media\models\Video::tableName() . '.[[id]]',
                    \app\modules\media\models\Video::tableName() . '.[[title]]',
                    \app\modules\media\models\Video::tableName() . '.[[text]]',
                    \app\modules\media\models\Video::tableName() . '.[[created_at]]',
                ])->where([
                    \app\modules\media\models\Video::tableName() . '.[[hidden]]' => \app\modules\media\models\Video::HIDDEN_NO,
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'Video';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function () {
                return null;
            },
            'updated_at' => function () {
                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function () {
                return '';

            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to(['/media/video/view', 'id' => $model['id']]);
            },
        ],
    ],
    /**
     * Ministry
     */
    [
        'dataProvider' => function () {
            return \app\modules\ministry\models\Ministry::find()
                ->select([
                    \app\modules\ministry\models\Ministry::tableName() . '.[[id]]',
                    \app\modules\ministry\models\Ministry::tableName() . '.[[title]]',
                    \app\modules\ministry\models\Ministry::tableName() . '.[[text]]',
                    \app\modules\ministry\models\Ministry::tableName() . '.[[url]]',
                    \app\modules\ministry\models\Ministry::tableName() . '.[[updated_at]]',
                ])
                ->where([
                    \app\modules\ministry\models\Ministry::tableName() . '.[[hidden]]' => \app\modules\ministry\models\Ministry::HIDDEN_NO,
                ])->andWhere([
                    'OR',
                    [\app\modules\ministry\models\Ministry::tableName() . '.[[type]]' => \app\modules\ministry\models\Ministry::TYPE_ARTICLE],
                    [\app\modules\ministry\models\Ministry::tableName() . '.[[type]]' => \app\modules\ministry\models\Ministry::TYPE_FOLDER],
                ]);
        },
        'fields' => [
            'module' => function () {
                return 'Pages';
            },
            'record_id' => function ($model) {
                return $model['id'];
            },
            'language' => function () {
                return Yii::$app->language;
            },
            'created_at' => function ($model) {
                return $model['updated_at'];
            },
            'updated_at' => function () {
                return null;
            },
            'title' => function ($model) {
                return $model['title'];
            },
            'description' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'data' => function ($model) {
                return implode(' ', [
                    $model['title'],
                    $model['text'],
                ]);
            },
            'url' => function ($model) {
                return \yii\helpers\Url::to(['/' . $model['url']]);
            },
        ],
    ],
];
