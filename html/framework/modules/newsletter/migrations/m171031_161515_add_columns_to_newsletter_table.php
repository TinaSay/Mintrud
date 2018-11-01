<?php

use yii\db\Migration;

class m171031_161515_add_columns_to_newsletter_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%newsletter}}', 'isNews', $this->smallInteger(1)->notNull()->defaultValue(0)->after('email'));
        $this->addColumn('{{%newsletter}}', 'isEvent', $this->smallInteger(1)->notNull()->defaultValue(0)->after('isNews'));
        $this->addColumn('{{%newsletter}}', 'time', $this->smallInteger(1)->notNull()->defaultValue(0)->after('isEvent'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%newsletter}}', 'isNews');
        $this->dropColumn('{{%newsletter}}', 'isEvent');
        $this->dropColumn('{{%newsletter}}', 'time');
    }

}
