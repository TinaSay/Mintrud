<?php

use yii\db\Migration;

class m170709_081908_questionnaire_question_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%questionnaire_question}}', 'parent_answer_id', $this->integer()->after('questionnaire_id'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%questionnaire_question}}', 'parent_answer_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170709_081908_questionnaire_question_add_column cannot be reverted.\n";

        return false;
    }
    */
}
