<?php

use yii\db\Migration;

class m170625_102737_client_email_confirm extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'email_verify',
            $this->smallInteger(1)->notNull()->defaultValue(1)->after('[[email]]'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%client}}', 'email_verify');
    }
}
