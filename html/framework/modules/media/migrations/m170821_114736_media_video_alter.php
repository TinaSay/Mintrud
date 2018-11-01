<?php

use yii\db\Migration;

class m170821_114736_media_video_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%video}}', 'text', 'MEDIUMTEXT');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%video}}', 'text', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170821_114736_media_video_alter cannot be reverted.\n";

        return false;
    }
    */
}
