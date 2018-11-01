<?php

use yii\db\Migration;

class m170626_070528_news_add_column extends Migration
{
    public function safeUp()
    {
        $this->truncateTable('{{%news}}');
        $this->addColumn('{{%news}}', 'url_id', $this->integer()->notNull()->after('directory_id'));

        $this->createIndex('ui-url_id-directory_id', '{{%news}}', ['directory_id', 'url_id'], true);
    }

    public function safeDown()
    {
        $this->dropIndex('ui-url_id-directory_id', '{{%news}}');
        $this->dropColumn('{{%news}}', 'url_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170626_070528_news_add_column cannot be reverted.\n";

        return false;
    }
    */
}
