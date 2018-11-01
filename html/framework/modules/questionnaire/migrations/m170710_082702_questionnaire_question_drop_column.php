<?php

use yii\db\Migration;

class m170710_082702_questionnaire_question_drop_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%questionnaire_question}}', 'parent_answer_id');
    }

    public function safeDown()
    {
        $this->addColumn('{{%questionnaire_question}}', 'parent_answer_id', $this->integer()->after('questionnaire_id'));
    }
}
