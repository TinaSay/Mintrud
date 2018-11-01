<?php

use yii\db\Migration;

class m170708_093639_questionnaire_result_answer_drop_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%questionnaire_result_answer}}', 'text');
    }

    public function safeDown()
    {
        $this->addColumn('{{%questionnaire_result_answer}}', 'text', $this->string(256)->notNull()->defaultValue('')->after('answer_id'));
    }
}
