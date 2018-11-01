<?php

use yii\db\Migration;
use yii\db\Schema;

class m160306_101127_magic extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%magic}}', 'module', Schema::TYPE_STRING . '(256) NOT NULL DEFAULT \'\'');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%magic}}', 'module', Schema::TYPE_STRING . '(64) NOT NULL DEFAULT \'\'');
    }
}
