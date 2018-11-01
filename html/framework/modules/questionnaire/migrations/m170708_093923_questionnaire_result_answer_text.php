<?php

use yii\db\Migration;

class m170708_093923_questionnaire_result_answer_text extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%questionnaire_result_answer_text}}',
            [
                'id' => $this->primaryKey(),
                'result_id' => $this->integer()->notNull(),
                'question_id' => $this->integer()->notNull(),
                'text' => $this->text()->notNull(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-questionnaire_result_answer_text-questionnaire_result',
            '{{%questionnaire_result_answer_text}}',
            'result_id',
            '{{%questionnaire_result}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-questionnaire_result_answer_text-questionnaire_question',
            '{{%questionnaire_result_answer_text}}',
            'question_id',
            '{{%questionnaire_question}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_result_answer_text-questionnaire_question',
            '{{%questionnaire_result_answer_text}}'
        );

        $this->dropForeignKey(
            'fk-questionnaire_result_answer_text-questionnaire_result',
            '{{%questionnaire_result_answer_text}}'
        );

        $this->dropTable('{{%questionnaire_result_answer_text}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170708_093923_questionnaire_result_answer_text cannot be reverted.\n";

        return false;
    }
    */
}
