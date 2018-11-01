<?php

use yii\db\Migration;

class m170620_081047_doc extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%doc}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'title' => $this->string()->notNull(),
                'url' => $this->string()->notNull(),
                'announce' => $this->string(124)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'language' => $this->string(8),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime()
            ],
            $options
        );

        $this->createIndex('i-directory', '{{%doc}}', 'directory_id');
        $this->createIndex('i-hidden', '{{%doc}}', 'hidden');
        $this->createIndex('i-created_by', '{{%doc}}', 'created_by');

        $this->addForeignKey(
            'fk-doc-directory',
            '{{%doc}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-doc-auth',
            '{{%doc}}',
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
            'fk-doc-directory',
            '{{%doc}}'
        );

        $this->dropForeignKey(
            'fk-doc-auth',
            '{{%doc}}'
        );

        $this->dropTable('{{%doc}}');
    }
}
