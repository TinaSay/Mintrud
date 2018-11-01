<?php

use yii\db\Migration;
use yii\db\Schema;

class m141222_194541_config extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%config}}',
            [
                'id' => Schema::TYPE_PK,
                'label' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\'',
                'name' => Schema::TYPE_STRING.'(64) NOT NULL DEFAULT \'\'',
                'value' => Schema::TYPE_TEXT.' NOT NULL',
            ],
            $options
        );

        $this->createIndex('config_name', '{{%config}}', ['name'], true);
    }

    public function safeDown()
    {
        $this->dropTable('{{%config}}');
    }
}
