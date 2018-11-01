<?php

use yii\db\Migration;

class m150828_215310_magic extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%magic}}', 'created_at', $this->dateTime()->null()->defaultValue(null));
        $this->addColumn('{{%magic}}', 'updated_at', $this->dateTime()->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%magic}}', 'updated_at');
        $this->dropColumn('{{%magic}}', 'created_at');
    }
}
