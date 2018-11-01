<?php

use yii\db\Migration;

class m170710_082946_questionnaire_question_answer extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%questionnaire_question_answer}}',
            [
                'id' => $this->primaryKey(),
                'question_id' => $this->integer()->notNull(),
                'answer_id' => $this->integer()->notNull(),
            ],
            $options
        );

        $this->createIndex('iu-question_id-answer_id', '{{%questionnaire_question_answer}}', ['question_id', 'answer_id']);

        $this->addForeignKey(
            'fk-questionnaire_question_answer-questionnaire_question',
            '{{%questionnaire_question_answer}}',
            'question_id',
            '{{%questionnaire_question}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-questionnaire_question_answer-questionnaire_answer',
            '{{%questionnaire_question_answer}}',
            'answer_id',
            '{{%questionnaire_answer}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_question_answer-questionnaire_answer',
            '{{%questionnaire_question_answer}}'
        );

        $this->dropForeignKey(
            'fk-questionnaire_question_answer-questionnaire_question',
            '{{%questionnaire_question_answer}}'
        );

        $this->dropTable('{{%questionnaire_question_answer}}');
    }
}
