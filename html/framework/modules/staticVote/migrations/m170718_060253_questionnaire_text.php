<?php

use app\modules\staticVote\models\StaticVoteQuestionnaire;
use yii\db\Migration;

class m170718_060253_questionnaire_text extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%static_vote_questionnaire}}', 'text', $this->text()->after('title'));
        $this->addColumn('{{%static_vote_questionnaire}}', 'alias',
            $this->string(127)->after('text')->notNull()->defaultValue('')
        );
        StaticVoteQuestionnaire::updateAll([
            'text' => '<p>Минтруд России проводит исследование сдерживающих факторов, препятствующих развитию внутренней трудовой миграции</p>',
            'alias' => 'labour',
        ]);

        $this->addColumn('{{%static_vote_question}}', 'hint',
            $this->string(255)->notNull()->defaultValue('')->after('question')
        );
        $this->addColumn('{{%static_vote_question}}', 'min_answers',
            $this->smallInteger()->notNull()->defaultValue(0)->after('answers')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%static_vote_questionnaire}}', 'text');
        $this->dropColumn('{{%static_vote_questionnaire}}', 'alias');

        $this->dropColumn('{{%static_vote_question}}', 'hint');
        $this->dropColumn('{{%static_vote_question}}', 'min_answers');

        echo "m170718_060253_questionnaire_text - reverted.\n";
    }
}
