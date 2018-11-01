<?php

use yii\db\Migration;

class m170623_121321_council_discussion_dates extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%council_discussion}}', 'date_begin', $this->date()->null()->defaultValue(null)->after('vote'));
        $this->addColumn('{{%council_discussion}}', 'date_end', $this->date()->null()->defaultValue(null)->after('date_begin'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%council_discussion}}', 'date_begin');
        $this->dropColumn('{{%council_discussion}}', 'date_end');

        echo "m170623_121321_council_discussion_dates - reverted.\n";

    }

}
