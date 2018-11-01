<?php

use yii\db\Migration;

class m170728_043001_document_widget_on_main extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=innoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%document_widget_on_main}}',
            [
                'id' => $this->primaryKey(),
                'type_document_id' => $this->integer()->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%document_widget_on_main}}', 'hidden');

        $this->addForeignKey(
            'fk-document_widget_on_main-type_document',
            '{{%document_widget_on_main}}',
            'type_document_id',
            '{{%type_document}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-document_widget_on_main-auth',
            '{{%document_widget_on_main}}',
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
            'fk-document_widget_on_main-type_document',
            '{{%document_widget_on_main}}'
        );

        $this->dropForeignKey(
            'fk-document_widget_on_main-auth',
            '{{%document_widget_on_main}}'
        );

        $this->dropTable('{{%document_widget_on_main}}');
    }
}
