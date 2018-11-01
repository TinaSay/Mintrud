<?php

use yii\db\Migration;

class m171011_124807_test_result extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing_result}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'testQuestionId' => $this->integer(),
            'time' => $this->integer()->null()->defaultValue('0'),
            'ip' => $this->bigInteger()->notNull()->defaultValue('0'),
            'createdBy' => $this->integer(),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );

        $this->createIndex('testId', '{{%testing_result}}', ['testId']);
        $this->addForeignKey(
            'testing_result_testId_testing_id',
            '{{%testing_result}}',
            'testId',
            '{{%testing}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->createIndex('testQuestionId', '{{%testing_result}}', ['testQuestionId']);
        $this->addForeignKey(
            'testing_result_test_q_id_testing_question_id',
            '{{%testing_result}}',
            'testQuestionId',
            '{{%testing_question}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_result_test_q_id_testing_question_id', '{{%testing_result}}');
        $this->dropForeignKey('testing_result_testId_testing_id', '{{%testing_result}}');
        $this->dropTable('{{%testing_result}}');
    }
}
