<?php

use yii\db\Migration;

class m171011_125051_test_result_answer extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing_result_answer}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'testQuestionId' => $this->integer(),
            'testQuestionAnswerId' => $this->integer(),
            'testResultId' => $this->integer(),
            'right' => $this->integer()->null()->defaultValue('0'),
        ],
            $options
        );

        $this->createIndex('testId', '{{%testing_result_answer}}', ['testId']);
        $this->addForeignKey(
            'testing_result_answer_test_id_testing_id',
            '{{%testing_result_answer}}',
            'testId',
            '{{%testing}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->createIndex('testQuestionId', '{{%testing_result_answer}}', ['testQuestionId']);
        $this->addForeignKey(
            'testing_result_answer_test_q_id_testing_question_id',
            '{{%testing_result_answer}}',
            'testQuestionId',
            '{{%testing_question}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->createIndex('testQuestionAnswerId', '{{%testing_result_answer}}', ['testQuestionAnswerId']);
        $this->addForeignKey(
            'testing_result_answer_test_a_id_testing_answer_id',
            '{{%testing_result_answer}}',
            'testQuestionAnswerId',
            '{{%testing_answer}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->createIndex('testResultId', '{{%testing_result_answer}}', ['testResultId']);
        $this->addForeignKey(
            'testing_result_answer_test_r_id_testing_result_id',
            '{{%testing_result_answer}}',
            'testResultId',
            '{{%testing_result}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_result_answer_test_r_id_testing_result_id', '{{%testing_result_answer}}');
        $this->dropForeignKey('testing_result_answer_test_a_id_testing_answer_id', '{{%testing_result_answer}}');
        $this->dropForeignKey('testing_result_answer_test_q_id_testing_question_id', '{{%testing_result_answer}}');
        $this->dropForeignKey('testing_result_answer_test_id_testing_id', '{{%testing_result_answer}}');
        $this->dropTable('{{%testing_result_answer}}');
    }
}
