<?php

use yii\db\Migration;

class m170721_125518_spider_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%spider}}', 'not_url_id', $this->integer()->notNull()->defaultValue(0)->after('is_parsed'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%spider}}', 'not_url_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_125518_spider_add_column cannot be reverted.\n";

        return false;
    }
    */
}
