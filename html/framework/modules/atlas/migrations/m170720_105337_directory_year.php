<?php

use app\modules\atlas\models\AtlasDirectoryYear;
use app\modules\auth\models\Auth;
use yii\db\Expression;
use yii\db\Migration;


class m170720_105337_directory_year extends Migration
{
    public function safeUp()
    {
        $list = [
            '2010',
            '2011',
            '2012',
            '2013',
            '2014',
            '2015',
            '2016',
        ];
        $pos = 0;
        $user = Auth::findOne(['login' => 'webmaster']);

        foreach ($list as $year) {
            $pos++;
            $this->getDb()->createCommand()->insert(AtlasDirectoryYear::tableName(), [
                'title' => $year,
                'position' => $pos,
                'hidden' => AtlasDirectoryYear::HIDDEN_NO,
                'language' => Yii::$app->language,
                'type' => AtlasDirectoryYear::getType(),
                'created_by' => $user->getId(),
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ])->execute();
        }
    }

    public function safeDown()
    {
        AtlasDirectoryYear::deleteAll(['type' => AtlasDirectoryYear::getType()]);

        echo "m170720_105337_directory_year - reverted.\n";

        return true;
    }
}
