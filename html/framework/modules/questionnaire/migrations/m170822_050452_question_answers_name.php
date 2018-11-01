<?php

use yii\db\Migration;

class m170822_050452_question_answers_name extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%questionnaire_question}}', 'name',
            $this->string(31)->notNull()->defaultValue('')->after('title'));

        $this->alterColumn('{{%questionnaire_question}}', 'title',
            $this->string(512)->notNull()->defaultValue(''));
        $this->addColumn('{{%questionnaire_question}}', 'hint',
            $this->string(255)->notNull()->defaultValue('')->after('title'));

        $this->addColumn('{{%questionnaire}}', 'name',
            $this->string(31)->notNull()->defaultValue('')->after('title'));

        $this->alterColumn('{{%questionnaire}}', 'description',
            $this->string(1024)->notNull()->defaultValue(''));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%questionnaire}}', 'name');
        $this->alterColumn('{{%questionnaire}}', 'description',
            $this->string(255)->notNull()->defaultValue(''));

        $this->dropColumn('{{%questionnaire_question}}', 'hint');
        $this->alterColumn('{{%questionnaire_question}}', 'title',
            $this->string(255)->notNull()->defaultValue(''));

        $this->dropColumn('{{%questionnaire_question}}', 'name');


        echo "m170822_050452_question_answers_name - reverted.\n";
    }

}
