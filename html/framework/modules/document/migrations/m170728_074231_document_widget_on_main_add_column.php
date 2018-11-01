<?php

use yii\db\Migration;

class m170728_074231_document_widget_on_main_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document_widget_on_main}}', 'position', $this->integer()->notNull()->defaultValue('0')->after('type_document_id'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%document_widget_on_main}}', 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170728_074231_document_widget_on_main_add_column cannot be reverted.\n";

        return false;
    }
    */
}
