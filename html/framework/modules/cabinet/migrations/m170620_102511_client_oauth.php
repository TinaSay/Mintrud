<?php

use yii\db\Migration;

class m170620_102511_client_oauth extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%client_oauth}}',
            [
                'id' => $this->primaryKey(),
                'client_id' => $this->integer(11)->notNull(),
                'source' => $this->string(256)->notNull(),
                'source_id' => $this->string(256)->notNull(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('client_id', '{{%client_oauth}}', ['client_id']);
        $this->addForeignKey(
            'client_oauth_client_id_client_id',
            '{{%client_oauth}}',
            ['client_id'],
            '{{%client}}',
            ['id'],
            'CASCADE',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('client_oauth_client_id_client_id', '{{%client_oauth}}');
        $this->dropTable('{{%client_oauth}}');
    }
}
