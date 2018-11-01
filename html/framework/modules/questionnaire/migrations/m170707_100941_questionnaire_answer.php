<?php

use yii\db\Migration;

class m170707_100941_questionnaire_answer extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%questionnaire_answer}}',
            [
                'id' => $this->primaryKey(),
                'question_id' => $this->integer()->notNull(),
                'title' => $this->string(255)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%questionnaire_answer}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%questionnaire_answer}}', ['created_by']);

        $this->addForeignKey(
            'fk-questionnaire_answer-questionnaire_question',
            '{{%questionnaire_answer}}',
            'question_id',
            '{{%questionnaire_question}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-questionnaire_answer-auth',
            '{{%questionnaire_answer}}',
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
            'fk-questionnaire_answer-questionnaire_question',
            '{{%questionnaire_answer}}'
        );
        $this->dropForeignKey(
            'fk-questionnaire_answer-auth',
            '{{%questionnaire_answer}}'
        );
        $this->dropTable('{{%questionnaire_answer}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_100941_questionnaire_answer cannot be reverted.\n";

        return false;
    }
    */
}
