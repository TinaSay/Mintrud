<?php

use yii\db\Migration;

class m170808_040758_directory_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%directory}}', 'title', $this->string(512)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%directory}}', 'title', $this->string(128)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170808_040758_directory_alter cannot be reverted.\n";

        return false;
    }
    */
}
