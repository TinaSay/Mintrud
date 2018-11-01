<?php

use yii\db\Migration;

class m171006_084755_redirect_create extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%redirect}}',
            [
                'id' => $this->primaryKey(),
                'from' => $this->string(1000)->notNull(),
                'redirect' => $this->string(1000)->notNull(),
                'hidden' => $this->integer()->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-redirect-hidden', '{{%redirect}}', 'hidden');

        $this->addForeignKey(
            'fk-redirect-auth',
            '{{%redirect}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-redirect-auth', '{{%redirect}}');
        $this->dropTable('{{%redirect}}');
    }
}
