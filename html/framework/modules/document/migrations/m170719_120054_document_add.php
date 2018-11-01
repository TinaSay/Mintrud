<?php

use yii\db\Migration;

class m170719_120054_document_add extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'ministry_number', $this->string(32)->after('number'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'ministry_number');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_120054_document_add cannot be reverted.\n";

        return false;
    }
    */
}
