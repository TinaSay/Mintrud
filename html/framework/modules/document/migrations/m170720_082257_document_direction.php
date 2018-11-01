<?php

use yii\db\Migration;

class m170720_082257_document_direction extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%document_direction}}',
            [
                'id' => $this->primaryKey(),
                'document_description_directory_id' => $this->integer()->notNull(),
                'title' => $this->string(256)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%document_direction}}', 'hidden');

        $this->addForeignKey(
            'fk-document_direction-document_description_directory_id',
            '{{%document_direction}}',
            'document_description_directory_id',
            '{{%document_description_directory}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'kf-document_direction-auth',
            '{{%document_direction}}',
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
            'kf-document_direction-auth',
            '{{%document_direction}}'
        );

        $this->dropForeignKey(
            'fk-document_direction-document_description_directory_id',
            '{{%document_direction}}'
        );

        $this->dropTable('{{%document_direction}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170720_082257_document_direction cannot be reverted.\n";

        return false;
    }
    */
}
