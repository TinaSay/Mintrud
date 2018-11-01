<?php

use yii\db\Migration;
use yii\db\Schema;

class m141227_103208_magic extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        $this->createTable(
            '{{%magic}}',
            [
                'id' => Schema::TYPE_PK,
                'module' => Schema::TYPE_STRING . '(64) NOT NULL DEFAULT \'\'',
                'group_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'',
                'record_id' => Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\'',
                'label' => Schema::TYPE_STRING . '(256) NOT NULL DEFAULT \'\'',
                'src' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\'',
                'preview' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\'',
                'mime' => Schema::TYPE_STRING . '(128) NOT NULL DEFAULT \'\'',
            ],
            $options
        );
        $this->createIndex('magic_module_group_id_record_id', '{{%magic}}', ['module', 'group_id', 'record_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%magic}}');
    }
}
