<?php

use yii\db\Migration;

class m170623_092625_council extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%council_member}}',
            [
                'id' => $this->primaryKey(),
                'login' => $this->string(32)->notNull(),
                'password' => $this->string(512)->notNull(),
                'auth_key' => $this->string(64)->null()->defaultValue(null),
                'access_token' => $this->string(128)->null()->defaultValue(null),
                'reset_token' => $this->string(128)->null()->defaultValue(null),
                'email' => $this->string(64)->null()->defaultValue(null),
                'additional_email' => $this->string(128)->null()->defaultValue(null),
                'name' => $this->string(255)->notNull()->defaultValue(''),
                'blocked' => $this->smallInteger(1)->notNull()->defaultValue(1),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('login', '{{%council_member}}', ['login'], true);
        $this->createIndex('email', '{{%council_member}}', ['email'], true);
        $this->createIndex('blocked', '{{%council_member}}', ['blocked']);
        $this->createIndex('created_by', '{{%council_member}}', 'created_by');

        $this->addForeignKey(
            'council_member_created_by_auth_id',
            '{{%council_member}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );


    }

    public function safeDown()
    {
        $this->dropForeignKey('council_member_created_by_auth_id', '{{%council_member}}');
        $this->dropTable('{{%council_member}}');
    }
}
