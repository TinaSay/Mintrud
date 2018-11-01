<?php

use yii\db\Migration;

class m170717_110949_spider_add_columns extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%spider}}', 'type_document', $this->string(256)->after('original'));
        $this->addColumn('{{%spider}}', 'direction', $this->string(256)->after('type_document'));
        $this->addColumn('{{%spider}}', 'organ', $this->string(256)->after('direction'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%spider}}', 'type_document');
        $this->dropColumn('{{%spider}}', 'direction');
        $this->dropColumn('{{%spider}}', 'organ');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170717_110949_spider_add_columns cannot be reverted.\n";

        return false;
    }
    */
}
