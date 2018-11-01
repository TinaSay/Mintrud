<?php

use yii\db\Migration;

class m150828_190737_config extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%config}}', 'created_at', $this->dateTime()->null()->defaultValue(null));
        $this->addColumn('{{%config}}', 'updated_at', $this->dateTime()->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%config}}', 'updated_at');
        $this->dropColumn('{{%config}}', 'created_at');
    }
}
