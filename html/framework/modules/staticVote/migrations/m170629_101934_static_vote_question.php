<?php

use yii\db\Migration;

class m170629_101934_static_vote_question extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%static_vote_question}}',
            [
                'id' => $this->primaryKey(),
                'questionnaire_id' => $this->integer()->null()->defaultValue(null),
                'question' => $this->string()->notNull()->defaultValue(''),
                'answers' => $this->text(),
                'input_type' => $this->string(31)->notNull(),
                'show_on_answer_check' => $this->string(31)->null(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('questionnaire_id', '{{%static_vote_question}}', 'questionnaire_id');

        $this->addForeignKey(
            'static_vote_question_questionnaire_id_static_vote_quest_id',
            '{{%static_vote_question}}',
            'questionnaire_id',
            '{{%static_vote_questionnaire}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('static_vote_question_questionnaire_id_static_vote_quest_id', '{{%static_vote_question}}');
        $this->dropTable('{{%static_vote_question}}');

        echo "m170629_101934_static_vote_question - reverted.\n";
    }

}
