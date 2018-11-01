<?php

use yii\db\Migration;

class m171011_103342_test_answers extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing_answer}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'testQuestionId' => $this->integer(),
            'title' => $this->string(512)->notNull(),
            'right' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );

        $this->createIndex('hidden', '{{%testing_answer}}', ['hidden']);
        $this->createIndex('testId', '{{%testing_answer}}', ['testId']);
        $this->addForeignKey(
            'testing_answer_testId_testing_id',
            '{{%testing_answer}}',
            'testId',
            '{{%testing}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->createIndex('testQuestionId', '{{%testing_answer}}', ['testQuestionId']);
        $this->addForeignKey(
            'testing_answer_test_q_id_testing_question_id',
            '{{%testing_answer}}',
            'testQuestionId',
            '{{%testing_question}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_answer_test_q_id_testing_question_id', '{{%testing_answer}}');
        $this->dropForeignKey('testing_answer_testId_testing_id', '{{%testing_answer}}');
        $this->dropTable('{{%testing_answer}}');
    }

}
