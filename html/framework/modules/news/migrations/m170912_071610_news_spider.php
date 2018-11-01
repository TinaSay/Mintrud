<?php

use yii\db\Migration;

class m170912_071610_news_spider extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%news_spider}}',
            [
                'id' => $this->primaryKey(),
                'url' => $this->string(1000)->notNull(),
                'url_id' => $this->integer()->notNull(),
                'directory_id' => $this->integer()->notNull(),
                'is_parsed' => $this->integer()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-news_spider-directory',
            '{{%news_spider}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-news_spider-directory',
            '{{%news_spider}}'
        );

        $this->dropTable('{{%news_spider}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170912_071610_news_spider cannot be reverted.\n";

        return false;
    }
    */
}
