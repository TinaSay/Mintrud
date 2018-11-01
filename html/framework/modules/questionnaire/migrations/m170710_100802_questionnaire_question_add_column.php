<?php

use yii\db\Migration;

class m170710_100802_questionnaire_question_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%questionnaire_question}}', 'parent_question_id', $this->integer()->after('type'));

        $this->addForeignKey(
            'fk-questionnaire_question-questionnaire_question',
            '{{%questionnaire_question}}',
            'parent_question_id',
            '{{%questionnaire_question}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_question-questionnaire_question',
            '{{%questionnaire_question}}'
        );
        $this->dropColumn('{{%questionnaire_question}}', 'parent_question_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_100802_questionnaire_question_add_column cannot be reverted.\n";

        return false;
    }
    */
}
