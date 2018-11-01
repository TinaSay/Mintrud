<?php

use app\modules\atlas\models\AtlasDirectoryAllowance;
use app\modules\auth\models\Auth;
use yii\db\Expression;
use yii\db\Migration;

class m170809_113828_directory_allowance extends Migration
{
    public function safeUp()
    {

        $list = [
            'family' => 'СЕМЬИ С ДЕТЬМИ',
            'disabled' => 'ИНВАЛИДЫ',
            'pensioners' => 'ПЕНСИОНЕРЫ',
            'chernobyltsy' => 'ЧЕРНОБЫЛЬЦЫ',
            'veterans' => 'ВЕТЕРАНЫ',
        ];
        $pos = 0;
        $user = Auth::findOne(['login' => 'webmaster']);

        foreach ($list as $code => $type) {
            $pos++;
            $this->getDb()->createCommand()->insert(AtlasDirectoryAllowance::tableName(), [
                'title' => $type,
                'position' => $pos,
                'hidden' => AtlasDirectoryAllowance::HIDDEN_NO,
                'language' => Yii::$app->language,
                'type' => AtlasDirectoryAllowance::getType(),
                'code' => $code,
                'created_by' => $user->getId(),
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ])->execute();

        }
    }

    public function safeDown()
    {

        AtlasDirectoryAllowance::deleteAll(['type' => AtlasDirectoryAllowance::getType()]);

        echo "m170720_103114_directory_rates - reverted.\n";

        return true;
    }
}
