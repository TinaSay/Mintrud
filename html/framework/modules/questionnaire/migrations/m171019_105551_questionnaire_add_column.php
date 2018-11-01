<?php

use yii\db\Migration;

class m171019_105551_questionnaire_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%questionnaire}}',
            'show_result',
            $this->integer()->notNull()->defaultValue('0')->after('description')
        );
    }

    public function safeDown()
    {
        $this->dropColumn(
            '{{%questionnaire}}',
            'show_result'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171019_105551_questionnaire_add_column cannot be reverted.\n";

        return false;
    }
    */
}
