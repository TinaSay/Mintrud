<?php

use yii\db\Migration;

class m170716_074444_document_description_directroy extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%document_description_directory}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'text' => $this->text()->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%document_description_directory}}', 'hidden');

        $this->addForeignKey(
            'fk-document_description_directory-directory',
            '{{%document_description_directory}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-document_description_directory-auth',
            '{{%document_description_directory}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_description_directory-auth',
            '{{%document_description_directory}}'
        );

        $this->dropForeignKey(
            'fk-document_description_directory-directory',
            '{{%document_description_directory}}'
        );

        $this->dropTable(
            '{{%document_description_directory}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170716_074444_document_description_directroy cannot be reverted.\n";

        return false;
    }
    */
}
