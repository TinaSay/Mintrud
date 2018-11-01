<?php

use yii\db\Migration;

class m170710_061329_questionnaire_question_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%questionnaire_question}}', 'position', $this->integer()->defaultValue('0')->after('type'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%questionnaire_question}}', 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_061329_questionnaire_question_add_column cannot be reverted.\n";

        return false;
    }
    */
}
