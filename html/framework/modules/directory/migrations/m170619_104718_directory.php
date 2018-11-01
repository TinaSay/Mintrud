<?php

use yii\db\Migration;

class m170619_104718_directory extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%directory}}',
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer(),
                'type' => $this->integer()->notNull(),
                'title' => $this->string(64)->notNull()->defaultValue(''),
                'fragment' => $this->string(10)->notNull(),
                'url' => $this->string(256)->notNull(),
                'position' => $this->integer()->notNull()->defaultValue('0'),
                'depth' => $this->integer()->notNull()->defaultValue('0'),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'language' => $this->string(8)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );
        $this->createIndex('i-position', '{{%directory}}', ['position']);
        $this->createIndex('i-hidden', '{{%directory}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%directory}}', ['created_by']);
        $this->createIndex('i-url', '{{%directory}}', 'url');
        $this->createIndex('i-type', '{{%directory}}', 'type');

        $this->addForeignKey(
            'fk-news_group-self',
            '{{%directory}}',
            'parent_id',
            '{{%directory}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-news_group-auth',
            '{{%directory}}',
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
            'fk-news_group-self',
            '{{%directory}}'
        );
        $this->dropForeignKey(
            'fk-news_group-auth',
            '{{%directory}}'
        );

        $this->dropTable('{{%directory}}');
    }
}
