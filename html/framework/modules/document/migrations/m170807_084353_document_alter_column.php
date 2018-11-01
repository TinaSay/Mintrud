<?php

use yii\db\Migration;

class m170807_084353_document_alter_column extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%document_direction}}', 'directory_id', $this->integer()->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%document_direction}}', 'directory_id', $this->integer());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170807_084353_document_alter_column cannot be reverted.\n";

        return false;
    }
    */
}
