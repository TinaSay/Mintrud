<?php

use yii\db\Migration;

class m170629_103422_static_vote_answers extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%static_vote_answers}}',
            [
                'id' => $this->primaryKey(),
                'questionnaire_id' => $this->integer()->null()->defaultValue(null),
                'questionnaire' => $this->text(),
                'ip' => $this->bigInteger(20)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );


        $this->createIndex('questionnaire_id', '{{%static_vote_answers}}', 'questionnaire_id');

        $this->addForeignKey(
            'static_vote_answers_questionnaire_id_static_vote_quest_id',
            '{{%static_vote_answers}}',
            'questionnaire_id',
            '{{%static_vote_questionnaire}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('static_vote_answers_questionnaire_id_static_vote_quest_id', '{{%static_vote_answers}}');
        $this->dropTable('{{%static_vote_answers}}');

        echo "m170629_103422_static_vote_answers - reverted.\n";

    }

}
