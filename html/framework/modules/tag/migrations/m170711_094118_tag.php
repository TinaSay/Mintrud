<?php

use yii\db\Migration;

class m170711_094118_tag extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%tag}}',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string(128)->notNull(),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-name', '{{%tag}}', 'name', true);

        $this->addForeignKey(
            'fk-tag-auth',
            '{{%tag}}',
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
            'fk-tag-auth',
            '{{%tag}}'
        );

        $this->dropTable('{{%tag}}');
    }
}
