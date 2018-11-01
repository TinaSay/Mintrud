<?php

use yii\db\Migration;

class m170619_121731_news extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%news}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'title' => $this->string(256)->notNull()->defaultValue(''),
                'text' => $this->text()->notNull(),
                'date' => $this->dateTime()->null()->defaultValue(null),
                'src' => $this->string(64)->notNull()->defaultValue(''),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('i-directory_id', '{{%news}}', ['directory_id']);
        $this->createIndex('i-date', '{{%news}}', ['date']);
        $this->createIndex('i-hidden', '{{%news}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%news}}', ['created_by']);

        $this->addForeignKey(
            'fk-news-directory',
            '{{%news}}',
            ['directory_id'],
            '{{%directory}}',
            ['id'],
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-news-auth',
            '{{%news}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-news-auth', '{{%news}}');
        $this->dropForeignKey('fk-news-directory', '{{%news}}');
        $this->dropTable('{{%news}}');
    }
}
