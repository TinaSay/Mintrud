<?php

use yii\db\Migration;

class m170821_095259_ministry_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%ministry}}', 'title', $this->string(2048)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%ministry}}', 'title', $this->string(1024)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170821_095259_ministry_alter cannot be reverted.\n";

        return false;
    }
    */
}
