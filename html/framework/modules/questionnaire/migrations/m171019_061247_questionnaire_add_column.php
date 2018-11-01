<?php

use yii\db\Migration;

class m171019_061247_questionnaire_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%questionnaire}}',
            'restriction_by_ip',
            $this->integer()->notNull()->defaultValue(0)->after('description')
        );
    }

    public function safeDown()
    {
        $this->dropColumn(
            '{{%questionnaire}}',
            'restriction_by_ip'
        );

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171019_061247_questionnaire_add_column cannot be reverted.\n";

        return false;
    }
    */
}
