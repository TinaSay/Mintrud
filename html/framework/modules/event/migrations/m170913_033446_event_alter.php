<?php

use yii\db\Migration;

class m170913_033446_event_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%event}}', 'text', 'MEDIUMTEXT NOT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%event}}', 'text', $this->text()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170913_033446_event_alter cannot be reverted.\n";

        return false;
    }
    */
}
