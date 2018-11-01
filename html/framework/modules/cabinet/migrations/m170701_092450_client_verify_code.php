<?php

use yii\db\Migration;

class m170701_092450_client_verify_code extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%client_verify_code}}', 'session_id', $this->string(128)->notNull()->after('[[id]]'));
        $this->addColumn('{{%client_verify_code}}', 'is_verify',
            $this->smallInteger(1)->notNull()->defaultValue(0)->after('[[code]]'));

        $this->createIndex('session_id', '{{%client_verify_code}}', ['session_id']);
        $this->createIndex('is_verify', '{{%client_verify_code}}', ['is_verify']);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%client_verify_code}}', 'is_verify');
        $this->dropColumn('{{%client_verify_code}}', 'session_id');
    }
}
