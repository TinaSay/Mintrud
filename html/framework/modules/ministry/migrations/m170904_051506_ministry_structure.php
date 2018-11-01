<?php

use app\modules\ministry\models\Ministry;
use app\modules\page\models\Page;
use app\modules\subdivision\models\Subdivision;
use yii\db\Expression;
use yii\db\Migration;

class m170904_051506_ministry_structure extends Migration
{
    public function safeUp()
    {

        $about = Ministry::findOne(['url' => 'ministry/about']);

        $text = \app\modules\page\models\Structure::find()->one();
        $this->insert(Ministry::tableName(), [
            'parent_id' => $about['id'],
            'depth' => $about['depth'] + 1,
            'title' => 'Структура Минтруда России',
            'url' => 'ministry/about/structure',
            'type' => Ministry::TYPE_FOLDER,
            'layout' => '//common',
            'text' => ($text ? $text['text'] : null),
            'position' => $about['position'] + 1,
            'hidden' => Ministry::HIDDEN_NO,
            'language' => 'ru-RU',
            'created_at' => '2012-08-21',
            'updated_at' => new Expression('NOW()'),
        ]);

        $parent_id = $this->getDb()->getLastInsertID();
        $pos = $about['position'] + 1;

        $subdivs = Subdivision::find()->orderBy(['position' => SORT_ASC])->all();
        foreach ($subdivs as $subdiv) {
            $pos++;
            $url = 'ministry/about/structure/' . $subdiv['fragment'];
            $this->insert(Ministry::tableName(), [
                'parent_id' => $parent_id,
                'depth' => $about['depth'] + 2,
                'title' => $subdiv['title'],
                'url' => $url,
                'type' => Ministry::TYPE_FOLDER,
                'layout' => '//common',
                'position' => $pos,
                'hidden' => Ministry::HIDDEN_NO,
                'language' => 'ru-RU',
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ]);
            $subdivId = $this->getDb()->getLastInsertID();

            $pages = Page::find()->where([
                'subdivision_id' => $subdiv['id'],
            ])->all();
            if ($pages) {
                $pos++;
                foreach ($pages as $page) {
                    $this->insert(Ministry::tableName(), [
                        'parent_id' => $subdivId,
                        'depth' => $about['depth'] + 3,
                        'title' => $page['title'],
                        'url' => $url . '/' . $page['alias'],
                        'type' => Ministry::TYPE_ARTICLE,
                        'text' => $page['text'],
                        'layout' => '//common',
                        'position' => $pos,
                        'hidden' => Ministry::HIDDEN_NO,
                        'language' => 'ru-RU',
                        'created_at' => new Expression('NOW()'),
                        'updated_at' => new Expression('NOW()'),
                    ]);
                }
            }
        }

    }

    public function safeDown()
    {
        Ministry::deleteAll([
            'LIKE',
            'url',
            'ministry/about/structure',
        ]);

        echo "m170904_051506_ministry_structure - reverted.\n";
    }
}
