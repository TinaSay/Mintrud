<?php

use yii\db\Migration;

class m170628_123424_client_verify_code extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%client_verify_code}}',
            [
                'id' => $this->primaryKey(),
                'attribute' => $this->string(64)->notNull(),
                'code' => $this->string(8)->notNull(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('attribute', '{{%client_verify_code}}', ['attribute']);
        $this->createIndex('code', '{{%client_verify_code}}', ['code']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%client_verify_code}}');
    }
}
