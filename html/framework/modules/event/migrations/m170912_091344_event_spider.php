<?php

use yii\db\Migration;

class m170912_091344_event_spider extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%event_spider_repeat}}',
            [
                'id' => $this->primaryKey(),
                'url' => $this->string(1000)->notNull(),
                'url_id' => $this->integer()->notNull(),
                'is_parsed' => $this->integer()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime()
            ],
            $options
        );
    }

    public function safeDown()
    {
        $this->dropTable(
            '{{%event_spider_repeat}}'
        );

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170912_091344_event_spider cannot be reverted.\n";

        return false;
    }
    */
}
