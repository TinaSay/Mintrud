<?php

use yii\db\Migration;

class m170620_092308_doc_drop_column extends Migration
{
    public function safeUp()
    {
        $this->dropColumn('{{%doc}}', 'language');
        $this->alterColumn('{{%doc}}', 'announce', $this->string(1024)->notNull());
    }

    public function safeDown()
    {
        $this->addColumn('{{%doc}}', 'language', $this->string(8)->after('created_by'));
        $this->alterColumn('{{%doc}}', 'announce', $this->string(124)->notNull());
    }
}
