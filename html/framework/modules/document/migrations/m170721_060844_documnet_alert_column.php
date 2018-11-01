<?php

use yii\db\Migration;

class m170721_060844_documnet_alert_column extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%document}}', 'title', $this->string(1024)->notNull());
    }

    public function safeDown()
    {
        $this->alterColumn('{{%document}}', 'title', $this->string(256)->notNull());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170721_060844_documnet_alert_column cannot be reverted.\n";

        return false;
    }
    */
}
