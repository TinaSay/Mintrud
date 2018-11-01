<?php

use yii\db\Migration;

class m170813_040643_ministry_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%ministry}}', 'text', 'MEDIUMTEXT');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%ministry}}', 'text', $this->text());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170813_040643_ministry_alter cannot be reverted.\n";

        return false;
    }
    */
}
