<?php

use yii\db\Migration;

class m170620_103019_client_log extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%client_log}}',
            [
                'id' => $this->primaryKey(),
                'client_id' => $this->integer(11)->null()->defaultValue(null),
                'status' => $this->smallInteger(1)->null()->defaultValue(null),
                'ip' => $this->bigInteger(20)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('client_id', '{{%client_log}}', ['client_id']);
        $this->addForeignKey(
            'client_log_client_id_client_id',
            '{{%client_log}}',
            ['client_id'],
            '{{%client}}',
            ['id'],
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('client_log_client_id_client_id', '{{%client_log}}');
        $this->dropTable('{{%client_log}}');
    }
}
