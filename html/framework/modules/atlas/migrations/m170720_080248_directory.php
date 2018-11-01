<?php

use yii\db\Migration;

class m170720_080248_directory extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%atlas_directory}}',
            [
                'id' => $this->primaryKey(),
                'type' => $this->integer()->notNull(),
                'title' => $this->string(64)->notNull()->defaultValue(''),
                'position' => $this->integer()->notNull()->defaultValue('0'),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'language' => $this->string(8)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );
        $this->createIndex('i-position', '{{%atlas_directory}}', ['position']);
        $this->createIndex('i-hidden', '{{%atlas_directory}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%atlas_directory}}', ['created_by']);
        $this->createIndex('i-type', '{{%atlas_directory}}', 'type');


        $this->addForeignKey(
            'fk-atlas_directory_created_by-auth_id',
            '{{%atlas_directory}}',
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
            'fk-atlas_directory_created_by-auth_id',
            '{{%atlas_directory}}'
        );

        $this->dropTable('{{%atlas_directory}}');
    }
}
