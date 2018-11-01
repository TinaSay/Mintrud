<?php

use yii\db\Migration;

class m170720_074213_favorite extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%favorite}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(256)->null()->defaultValue(null),
                'url' => $this->string(256)->notNull(),
                'language' => $this->string(8)->null()->defaultValue(null),
                'createdBy' => $this->integer()->null()->defaultValue(null),
                'createdAt' => $this->dateTime()->null()->defaultValue(null),
                'updatedAt' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('url', '{{%favorite}}', ['url']);
        $this->createIndex('language', '{{%favorite}}', ['language']);
        $this->createIndex('createdBy', '{{%favorite}}', ['createdBy']);

        $this->addForeignKey(
            'fk-favorite-createdBy-client-id',
            '{{%favorite}}',
            'createdBy',
            '{{%client}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-favorite-createdBy-client-id', '{{%favorite}}');
        $this->dropTable('{{%favorite}}');
    }
}
