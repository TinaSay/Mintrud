<?php

use yii\db\Migration;

class m170707_100901_questionnaire_question extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%questionnaire_question}}',
            [
                'id' => $this->primaryKey(),
                'questionnaire_id' => $this->integer()->notNull(),
                'title' => $this->string(255)->notNull(),
                'type' => $this->integer()->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%questionnaire_question}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%questionnaire_question}}', ['created_by']);
        $this->addForeignKey(
            'fk-questionnaire_question_questionnaire',
            '{{%questionnaire_question}}',
            'questionnaire_id',
            '{{%questionnaire}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-questionnaire_question-auth',
            '{{%questionnaire_question}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_question_questionnaire',
            '{{%questionnaire_question}}'
        );

        $this->dropForeignKey(
            'fk-questionnaire_question-auth',
            '{{%questionnaire_question}}'
        );

        $this->dropTable('{{%questionnaire_question}}');
    }
}
