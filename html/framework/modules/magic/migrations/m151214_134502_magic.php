<?php

use yii\db\Migration;
use yii\db\Schema;

class m151214_134502_magic extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%magic}}', 'hint', Schema::TYPE_STRING . '(256) NOT NULL DEFAULT \'\' AFTER `label`');
        $this->addColumn('{{%magic}}', 'size', Schema::TYPE_INTEGER . '(11) NOT NULL DEFAULT \'0\' AFTER `src`');
        $this->addColumn('{{%magic}}', 'extension', Schema::TYPE_STRING . '(8) NOT NULL DEFAULT \'\' AFTER `size`');
    }

    public function safeDown()
    {
        $this->dropColumn('{{%magic}}', 'extension');
        $this->dropColumn('{{%magic}}', 'size');
        $this->dropColumn('{{%magic}}', 'hint');
    }
}
