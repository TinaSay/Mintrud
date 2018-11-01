<?php

use yii\db\Migration;

class m170703_074627_doc_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%doc}}', 'date', $this->date()->after('announce'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%doc}}', 'date');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170703_074627_doc_add_column cannot be reverted.\n";

        return false;
    }
    */
}
