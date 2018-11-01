<?php

use yii\db\Migration;

class m170822_045057_media_audio_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%audio}}', 'text', 'MEDIUMTEXT');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%audio}}', 'text', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170822_045057_media_audio_alter cannot be reverted.\n";

        return false;
    }
    */
}
