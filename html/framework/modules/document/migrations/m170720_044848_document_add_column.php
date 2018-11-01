<?php

use yii\db\Migration;

class m170720_044848_document_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'url_id', $this->integer()->notNull()->after('id'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'url_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_044848_document_add_column cannot be reverted.\n";

        return false;
    }
    */
}
