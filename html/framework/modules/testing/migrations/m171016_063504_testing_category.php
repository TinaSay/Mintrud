<?php

use yii\db\Migration;

class m171016_063504_testing_category extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing_question_category}}', [
            'id' => $this->primaryKey(),
            'testId' => $this->integer(),
            'title' => $this->string(512)->notNull(),
            'limit' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );


        $this->createIndex('testId', '{{%testing_question_category}}', ['testId']);
        $this->addForeignKey(
            'testing_question_category_testId_testing_id',
            '{{%testing_question_category}}',
            'testId',
            '{{%testing}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addColumn('{{%testing_question}}', 'categoryId',
            $this->integer()->null()->defaultValue(null)->after('testId')
        );
        $this->createIndex('categoryId', '{{%testing_question}}', ['categoryId']);
        $this->addForeignKey(
            'testing_question_categoryId_testing_q_category_id',
            '{{%testing_question}}',
            'categoryId',
            '{{%testing_question_category}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_question_categoryId_testing_q_category_id', '{{%testing_question}}');
        $this->dropColumn('{{%testing_question}}', 'categoryId');

        $this->dropForeignKey('testing_question_category_testId_testing_id', '{{%testing_question_category}}');
        $this->dropTable('{{%testing_question_category}}');
    }
}
