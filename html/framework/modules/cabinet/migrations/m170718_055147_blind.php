<?php

use yii\db\Migration;

class m170718_055147_blind extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'blind',
            $this->string(64)->null()->defaultValue(null)->after('[[reset_token]]'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%client}}', 'blind');
    }
}
