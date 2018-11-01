<?php

use yii\db\Migration;

class m171011_084326_document_news_direction_index extends Migration
{
    public function safeUp()
    {
        $this->createIndex(
            'iu-document_news_direction-news_id-direction_id',
            '{{%document_news_direction}}',
            ['news_id', 'direction_id'],
            true
        );
    }

    public function safeDown()
    {
        $this->dropIndex(
            'iu-document_news_direction-news_id-direction_id',
            '{{%document_news_direction}}'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171011_084326_document_news_direction_index cannot be reverted.\n";

        return false;
    }
    */
}
