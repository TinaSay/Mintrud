<?php

use yii\db\Migration;

class m171023_092447_appeal_ok extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%appeal}}', 'ok',
            $this->smallInteger(1)->notNull()->defaultValue(1)->after('status')
        );

        $this->createIndex('ok', '{{%appeal}}', ['ok']);
    }

    public function safeDown()
    {
        $this->dropColumn('{{%appeal}}', 'ok');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171023_092447_appeal_ok cannot be reverted.\n";

        return false;
    }
    */
}
