<?php

use yii\db\Migration;

class m170912_030334_ministry_spider extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%ministry_spider}}',
            [
                'id' => $this->primaryKey(),
                'url' => $this->string(1000)->notNull(),
                'is_parsed' => $this->integer()->notNull()->defaultValue(0),
                'folder_id' => $this->integer()->notNull(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-ministry_spider-ministry',
            '{{%ministry_spider}}',
            'folder_id',
            '{{%ministry}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-ministry_spider-ministry',
            '{{%ministry_spider}}'
        );

        $this->dropTable('{{%ministry_spider}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170912_030334_ministry_spider cannot be reverted.\n";

        return false;
    }
    */
}
