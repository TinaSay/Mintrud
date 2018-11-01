<?php

use yii\db\Migration;

class m170713_113256_document_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'announce', $this->string(1024)->notNull()->after('title'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'announce');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170713_113256_document_add_column cannot be reverted.\n";

        return false;
    }
    */
}
