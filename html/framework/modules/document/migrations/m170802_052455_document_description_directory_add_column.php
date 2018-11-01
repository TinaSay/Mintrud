<?php

use yii\db\Migration;

class m170802_052455_document_description_directory_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%document_description_directory}}',
            'news_directory_id',
            $this->integer()->after('directory_id')
        );

        $this->addForeignKey(
            'fk-document_description_directory-news_directory',
            '{{%document_description_directory}}',
            'news_directory_id',
            '{{%directory}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_description_directory-news_directory',
            '{{%document_description_directory}}'
        );

        $this->dropColumn('{{%document_description_directory}}', 'news_directory_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170802_052455_document_description_directory_add_column cannot be reverted.\n";

        return false;
    }
    */
}
