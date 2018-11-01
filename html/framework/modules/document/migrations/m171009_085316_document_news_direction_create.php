<?php

use yii\db\Migration;

class m171009_085316_document_news_direction_create extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%document_news_direction}}',
            [
                'id' => $this->primaryKey(),
                'news_id' => $this->integer()->notNull(),
                'direction_id' => $this->integer()->notNull(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-document_news_direction-document_direction',
            '{{%document_news_direction}}',
            'direction_id',
            '{{%document_direction}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-document_news_direction-news',
            '{{%document_news_direction}}',
            'news_id',
            '{{%news}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_news_direction-document_direction',
            '{{%document_news_direction}}'
        );

        $this->dropForeignKey(
            'fk-document_news_direction-news',
            '{{%document_news_direction}}'
        );

        $this->dropTable('{{%document_news_direction}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171009_085316_document_news_direction_create cannot be reverted.\n";

        return false;
    }
    */
}
