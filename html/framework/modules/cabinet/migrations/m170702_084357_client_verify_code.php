<?php

use yii\db\Migration;

class m170702_084357_client_verify_code extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%client_verify_code}}', 'retry',
            $this->smallInteger(1)->notNull()->defaultValue(0)->after('[[is_verify]]'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%client_verify_code}}', 'retry');
    }
}
