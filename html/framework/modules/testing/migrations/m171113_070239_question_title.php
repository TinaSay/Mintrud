<?php

use yii\db\Migration;

class m171113_070239_question_title extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%testing_question}}', 'title', $this->string(4096)->notNull());
        $this->alterColumn('{{%testing_answer}}', 'title', $this->string(4096)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%testing_question}}', 'title', $this->string(512)->notNull());
        $this->alterColumn('{{%testing_answer}}', 'title', $this->string(512)->notNull());
    }

}
