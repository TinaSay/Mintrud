<?php

use yii\db\Migration;

class m170707_101120_questionnaire_result_answer extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%questionnaire_result_answer}}',
            [
                'id' => $this->primaryKey(),
                'result_id' => $this->integer()->notNull(),
                'question_id' => $this->integer()->notNull(),
                'answer_id' => $this->integer()->notNull(),
                'text' => $this->string(255)->notNull()->defaultValue(''),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-questionnaire_result_answer-questionnaire_result',
            '{{%questionnaire_result_answer}}',
            'result_id',
            '{{%questionnaire_result}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-questionnaire_result_answer-questionnaire_question',
            '{{%questionnaire_result_answer}}',
            'question_id',
            '{{%questionnaire_question}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );


        $this->addForeignKey(
            'fk-questionnaire_result_answer-questionnaire_answer',
            '{{%questionnaire_result_answer}}',
            'answer_id',
            '{{%questionnaire_answer}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_result_answer-questionnaire_answer',
            '{{%questionnaire_result_answer}}'
        );
        $this->dropForeignKey(
            'fk-questionnaire_result_answer-questionnaire_question',
            '{{%questionnaire_result_answer}}'
        );
        $this->dropForeignKey(
            'fk-questionnaire_result_answer-questionnaire_result',
            '{{%questionnaire_result_answer}}'
        );
        $this->dropTable('{{%questionnaire_result_answer}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_101120_questionnaire_result_answer cannot be reverted.\n";

        return false;
    }
    */
}
