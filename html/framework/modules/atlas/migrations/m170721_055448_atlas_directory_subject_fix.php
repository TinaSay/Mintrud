<?php

use app\modules\atlas\models\AtlasDirectorySubjectRf;
use yii\db\Migration;

class m170721_055448_atlas_directory_subject_fix extends Migration
{
    public function safeUp()
    {
        AtlasDirectorySubjectRf::updateAll(['title' => 'Владимирская область'], ['title' => 'Владимирская  область']);
        AtlasDirectorySubjectRf::updateAll(['title' => 'Ханты-Мансийский АО'], ['title' => 'Ханты-Мансийскоий автономный округ (Югра)']);
    }

    public function safeDown()
    {
        echo "m170721_055448_atlas_directory_subject_fix cannot be reverted.\n";

        return true;
    }

}
