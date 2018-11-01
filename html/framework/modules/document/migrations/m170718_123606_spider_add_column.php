<?php

use yii\db\Migration;

class m170718_123606_spider_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%spider}}', 'theme', $this->string(256)->after('direction'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%spider}}', 'theme');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170718_123606_spider_add_column cannot be reverted.\n";

        return false;
    }
    */
}
