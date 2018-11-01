<?php

use yii\db\Migration;

class m170719_130055_document_alter_column extends Migration
{
    public function safeUp()
    {
        $this->execute('ALTER TABLE cmf2_document MODIFY COLUMN text MEDIUMTEXT NOT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%document}}', 'text', $this->text()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_130055_document_alter_column cannot be reverted.\n";

        return false;
    }
    */
}
