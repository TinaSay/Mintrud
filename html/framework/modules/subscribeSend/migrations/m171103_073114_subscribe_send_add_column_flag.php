<?php

use yii\db\Migration;

class m171103_073114_subscribe_send_add_column_flag extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%subscribe_send}}', 'statusTime', $this->smallInteger()->notNull()->defaultValue(0)
            ->after('recordId'));

    }

    public function safeDown()
    {
        $this->dropColumn('{{%subscribe_send}}', 'statusTime');
    }
}
