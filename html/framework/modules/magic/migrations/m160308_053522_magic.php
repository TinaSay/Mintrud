<?php

use yii\db\Migration;
use yii\db\Schema;

class m160308_053522_magic extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%magic}}', 'label', Schema::TYPE_STRING . '(1024) NOT NULL DEFAULT \'\'');
        $this->alterColumn('{{%magic}}', 'hint', Schema::TYPE_STRING . '(1024) NOT NULL DEFAULT \'\'');
    }

    public function safeDown()
    {
        $this->alterColumn('{{%magic}}', 'label', Schema::TYPE_STRING . '(256) NOT NULL DEFAULT \'\'');
        $this->alterColumn('{{%magic}}', 'hint', Schema::TYPE_STRING . '(256) NOT NULL DEFAULT \'\'');
    }
}
