<?php

use yii\db\Migration;

class m171024_160135_subscribe_send extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%subscribe_send}}', [
            'id' => $this->primaryKey(),
            'model' => $this->string(128),
            'recordId' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%subscribe_send}}');
    }
}
