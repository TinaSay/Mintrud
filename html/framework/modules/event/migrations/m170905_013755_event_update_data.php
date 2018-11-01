<?php

use yii\db\Migration;

class m170905_013755_event_update_data extends Migration
{
    public function safeUp()
    {
        $this->update('{{%event}}', ['language' => 'en-US'], ['language' => 'en-EN']);
    }

    public function safeDown()
    {
        $this->update('{{%event}}', ['language' => 'en-EN'], ['language' => 'en-US']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170905_013755_event_update_data cannot be reverted.\n";

        return false;
    }
    */
}
