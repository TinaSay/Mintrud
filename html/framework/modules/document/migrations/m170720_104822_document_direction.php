<?php

use yii\db\Migration;

class m170720_104822_document_direction extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%document_document_direction}}',
            [
                'id' => $this->primaryKey(),
                'document_id' => $this->integer(),
                'document_direction_id' => $this->integer(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-document_document_direction-document',
            '{{%document_document_direction}}',
            'document_id',
            '{{%document}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-document_document_direction-document_direction',
            '{{%document_document_direction}}',
            'document_direction_id',
            '{{%document_direction}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_document_direction-document_direction',
            '{{%document_document_direction}}'
        );

        $this->dropForeignKey(
            'fk-document_document_direction-document',
            '{{%document_document_direction}}'
        );

        $this->dropTable('{{%document_document_direction}}');
    }
}
