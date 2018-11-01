<?php

use yii\db\Migration;

class m170803_070500_event_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%event}}', 'begin_date', $this->date()->after('date'));
        $this->addColumn('{{%event}}', 'finish_date', $this->date()->after('begin_date'));
        $this->addColumn('{{%event}}', 'place', $this->string(128)->after('text'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%event}}', 'begin_date');
        $this->dropColumn('{{%event}}', 'finish_date');
        $this->dropColumn('{{%event}}', 'place');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170803_070500_event_add_column cannot be reverted.\n";

        return false;
    }
    */
}
