<?php

use yii\db\Migration;

class m171018_061116_newsletter extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%newsletter}}',
            [
                'id' => $this->primaryKey(),
                'ip' => $this->bigInteger(20)->null()->defaultValue(null),
                'email' => $this->string(64)->unique(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%newsletter}}');
    }
}
