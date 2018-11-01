<?php

use yii\db\Migration;

class m170905_050058_url_index extends Migration
{
    public function safeUp()
    {
        $this->dropIndex('url', '{{%ministry}}');

        $this->createIndex('url', '{{%ministry}}', ['url']);
    }

    public function safeDown()
    {
        $this->dropIndex('url', '{{%ministry}}');

        $this->createIndex('url', '{{%ministry}}', ['url'], true);

        echo "m170905_050058_url_index - reverted.\n";
    }


}
