<?php

use yii\db\Migration;

class m170719_020512_spider_file extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%spider_file}}',
            [
                'id' => $this->primaryKey(),
                'spider_id' => $this->integer()->notNull(),
                'origin' => $this->string(1024)->notNull(),
                'is_parsed' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-spider_file-spider',
            '{{%spider_file}}',
            'spider_id',
            '{{%spider}}',
            'id',
            'CASCADE',
            'CASCADE'
        );


    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-spider_file-spider',
            '{{%spider_file}}'
        );

        $this->dropTable('{{%spider_file}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170719_020512_spider_file cannot be reverted.\n";

        return false;
    }
    */
}
