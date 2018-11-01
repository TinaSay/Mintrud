<?php

use yii\db\Migration;

class m170719_053131_spider_file_add extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%spider_file}}', 'title', $this->string(1024)->notNull()->after('origin'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%spider_file}}', 'title');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_053131_spider_file_add cannot be reverted.\n";

        return false;
    }
    */
}
