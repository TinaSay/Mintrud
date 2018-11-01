<?php

use yii\db\Migration;

class m170623_094930_event_drop_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%event}}', 'src');
    }

    public function safeDown()
    {
        $this->addColumn('{{%event}}', 'src', $this->string(256)->after('text'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170623_094930_event_drop_column cannot be reverted.\n";

        return false;
    }
    */
}
