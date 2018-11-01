<?php

use yii\db\Migration;

/**
 * Class m171117_053011_auth_add_column_ip
 */
class m171117_053011_auth_add_column_ip extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%auth}}', 'ip', $this->bigInteger()->after('email'));
        $this->addColumn('{{%auth}}', 'bind_ip', $this->integer()->notNull()->defaultValue(0)->after('ip'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%auth}}', 'ip');
        $this->dropColumn('{{%auth}}', 'bind_ip');
    }
}
