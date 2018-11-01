<?php

use yii\db\Migration;

class m170721_115943_document_alert_column extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%document}}', 'announce', $this->text()->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%document}}', 'announce', $this->string(1024)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_115943_document_alert_column cannot be reverted.\n";

        return false;
    }
    */
}
