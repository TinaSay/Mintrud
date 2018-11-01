<?php

use app\modules\atlas\models\AtlasDirectoryYear;
use app\modules\auth\models\Auth;
use yii\db\Expression;
use yii\db\Migration;

class m170727_113320_atlas_directory_year_code extends Migration
{
    public function safeUp()
    {
        $list = [
            '2010' => '2010 г.',
            '2011' => '2011 г.',
            '2012' => '2012 г.',
            '2013' => '2013 г.',
            '2014' => '2014 г.',
            '2015' => '2015 г.',
            '2016' => '2016 г.',
        ];
        $pos = 0;
        $user = Auth::findOne(['login' => 'webmaster']);

        foreach ($list as $code => $year) {
            $pos++;
            $exists = AtlasDirectoryYear::find()->select(['id'])->where([
                'like',
                'title',
                $code,
                false,
            ])->andWhere(
                [
                    'type' => AtlasDirectoryYear::getType(),
                ]
            )->limit(1)->column();
            if ($exists) {
                $this->update(
                    AtlasDirectoryYear::tableName(),
                    [
                        'code' => $code,
                        'title' => $year,
                    ],
                    [
                        'id' => current($exists),
                    ]
                );
            } else {
                $this->insert(AtlasDirectoryYear::tableName(), [
                    'title' => $year,
                    'code' => $code,
                    'position' => $pos,
                    'hidden' => AtlasDirectoryYear::HIDDEN_NO,
                    'language' => Yii::$app->language,
                    'type' => AtlasDirectoryYear::getType(),
                    'created_by' => $user->getId(),
                    'created_at' => new Expression('NOW()'),
                    'updated_at' => new Expression('NOW()'),
                ]);
            }
        }
    }

    public function safeDown()
    {
        AtlasDirectoryYear::deleteAll(['type' => AtlasDirectoryYear::getType()]);

        echo "m170720_105337_directory_year - reverted.\n";

        return true;
    }
}
