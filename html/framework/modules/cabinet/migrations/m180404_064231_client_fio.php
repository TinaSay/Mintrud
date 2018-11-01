<?php

use yii\db\Migration;

/**
 * Class m180404_064231_client_fio
 */
class m180404_064231_client_fio extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%client}}', 'lastName',
            $this->string(128)->null()->defaultValue(null)->after('[[email_verify]]'));
        $this->addColumn('{{%client}}', 'firstName',
            $this->string(128)->null()->defaultValue(null)->after('[[lastName]]'));
        $this->addColumn('{{%client}}', 'middleName',
            $this->string(128)->null()->defaultValue(null)->after('[[firstName]]'));

    }

    public function safeDown()
    {
        $this->dropColumn('{{%client}}', 'lastName');
        $this->dropColumn('{{%client}}', 'firstName');
        $this->dropColumn('{{%client}}', 'middleName');
    }
}
