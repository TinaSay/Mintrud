<?php

use yii\db\Migration;

class m170623_074120_event_pupulate_data extends Migration
{

    public function safeUp()
    {
        $this->alterColumn('{{%event}}', 'title', $this->string(512)->notNull());
        $this->alterColumn('{{%event}}', 'hidden', $this->integer()->notNull()->defaultValue(0));
    }

    public function safeDown()
    {
        $this->alterColumn('{{%event}}', 'title', $this->string(256)->notNull());
        $this->alterColumn('{{%event}}', 'hidden', $this->integer()->notNull()->defaultValue(1));
    }
}
