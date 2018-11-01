<?php

use yii\db\Migration;

class m170620_101441_client extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%client}}',
            [
                'id' => $this->primaryKey(),
                'login' => $this->string(32)->null(),
                'password' => $this->string(512)->notNull(),
                'auth_key' => $this->string(64)->null()->defaultValue(null),
                'access_token' => $this->string(128)->null()->defaultValue(null),
                'reset_token' => $this->string(128)->null()->defaultValue(null),
                'email' => $this->string(64)->null()->defaultValue(null),
                'blocked' => $this->smallInteger(1)->notNull()->defaultValue(1),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('login', '{{%client}}', ['login'], true);
        $this->createIndex('auth_key', '{{%client}}', ['auth_key'], true);
        $this->createIndex('access_token', '{{%client}}', ['access_token'], true);
        $this->createIndex('reset_token', '{{%client}}', ['reset_token'], true);
        $this->createIndex('blocked', '{{%client}}', ['blocked']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%client}}');
    }
}
