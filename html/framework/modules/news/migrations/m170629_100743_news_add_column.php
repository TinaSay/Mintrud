<?php

use yii\db\Migration;

class m170629_100743_news_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%news_widget_on_main}}', 'position', $this->integer()->notNull()->defaultValue('0')->after('title'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%news_widget_on_main}}', 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170629_100743_news_add_column cannot be reverted.\n";

        return false;
    }
    */
}