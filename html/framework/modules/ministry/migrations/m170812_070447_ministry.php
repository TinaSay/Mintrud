<?php

use yii\db\Migration;

class m170812_070447_ministry extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%ministry}}',
            [
                'id' => $this->primaryKey(),
                'parent_id' => $this->integer()->null()->defaultValue(null),
                'title' => $this->string(512)->notNull(),
                'type' => $this->smallInteger(1)->notNull()->defaultValue(1),// default: folder
                'text' => $this->text(),
                'url' => $this->string(255)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
                'depth' => $this->integer()->notNull()->defaultValue(0),
                'position' => $this->integer()->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('url', '{{%ministry}}', ['url'], true);
        $this->createIndex('parent_id', '{{%ministry}}', ['parent_id']);
        $this->createIndex('type', '{{%ministry}}', ['type']);
        $this->createIndex('hidden', '{{%ministry}}', ['hidden']);
        $this->createIndex('created_by', '{{%ministry}}', 'created_by');

        $this->addForeignKey(
            'ministry_created_by_auth_id',
            '{{%ministry}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'ministry_parent_id_ministry_id',
            '{{%ministry}}',
            'parent_id',
            '{{%ministry}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('ministry_parent_id_ministry_id', '{{%ministry}}');
        $this->dropForeignKey('ministry_created_by_auth_id', '{{%ministry}}');
        $this->dropTable('{{%ministry}}');
    }
}
