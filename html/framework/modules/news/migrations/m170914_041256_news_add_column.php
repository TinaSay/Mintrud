<?php

use yii\db\Migration;

class m170914_041256_news_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%news}}', 'show_on_main', $this->integer()->notNull()->defaultValue(0)->after('src'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'show_on_main');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170914_041256_news_add_column cannot be reverted.\n";

        return false;
    }
    */
}
