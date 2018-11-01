<?php

use yii\db\Migration;

class m170713_065455_document extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%document}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'type_document_id' => $this->integer()->notNull(),
                'organ_id' => $this->integer(),
                'title' => $this->string(256)->notNull(),
                'text' => $this->text()->notNull(),
                'date' => $this->date(),
                'number' => $this->string(32),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%document}}', 'hidden');

        $this->addForeignKey(
            'fk-document-directory',
            '{{%document}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-document-type_document',
            '{{%document}}',
            'type_document_id',
            '{{%type_document}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-document-organ',
            '{{%document}}',
            'organ_id',
            '{{%organ}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-document-auth',
            '{{%document}}',
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
            'fk-document-auth',
            '{{%document}}'
        );

        $this->dropForeignKey(
            'fk-document-organ',
            '{{%document}}'
        );

        $this->dropForeignKey(
            'fk-document-type_document',
            '{{%document}}'
        );

        $this->dropForeignKey(
            'fk-document-directory',
            '{{%document}}'
        );

        $this->dropTable('{{%document}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170713_065455_document cannot be reverted.\n";

        return false;
    }
    */
}
