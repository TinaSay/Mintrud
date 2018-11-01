<?php

use yii\db\Migration;
use yii\db\Schema;

class m150829_070009_magic extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%magic}}', 'language', Schema::TYPE_STRING . '(8) NOT NULL DEFAULT \'\' AFTER `position`');

        $this->createIndex('language', '{{%magic}}', ['language']);

    }

    public function safeDown()
    {
        $this->dropColumn('{{%magic}}', 'language');
    }
}
