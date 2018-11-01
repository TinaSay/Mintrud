<?php

use yii\db\Migration;

class m170808_034625_document_directory_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document_direction}}', 'doc_directory_id', $this->integer()->after('news_directory_id'));

        $this->addForeignKey(
            'fk-document_directory-doc_directory',
            '{{%document_direction}}',
            'doc_directory_id',
            '{{%directory}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_directory-doc_directory',
            '{{%document_direction}}'
        );

        $this->dropColumn('{{%document_direction}}', 'doc_directory_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170808_034625_document_directory_add_column cannot be reverted.\n";

        return false;
    }
    */
}
