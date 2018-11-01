<?php

use yii\db\Migration;

class m171011_062935_test_questions extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing_question}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'title' => $this->string(512)->notNull(),
            'multiple' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );


        $this->createIndex('hidden', '{{%testing_question}}', ['hidden']);
        $this->createIndex('testId', '{{%testing_question}}', ['testId']);
        $this->addForeignKey(
            'testing_question_testId_testing_id',
            '{{%testing_question}}',
            'testId',
            '{{%testing}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_question_testId_testing_id', '{{%testing_question}}');
        $this->dropTable('{{%testing_question}}');
    }

}
