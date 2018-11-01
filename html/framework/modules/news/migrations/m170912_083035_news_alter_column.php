<?php

use yii\db\Migration;

class m170912_083035_news_alter_column extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%news}}', 'text', 'MEDIUMTEXT NOT NULL');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%news}}', 'text', $this->text()->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170912_083035_news_alter_column cannot be reverted.\n";

        return false;
    }
    */
}
