<?php

use yii\db\Migration;

class m170627_054626_news_widget_on_main extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;


        $this->createTable(
            '{{%news_widget_on_main}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'title' => $this->string(256)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%news_widget_on_main}}', 'hidden');
        $this->createIndex('i-created_by', '{{%news_widget_on_main}}', 'created_by');

        $this->addForeignKey(
            'fk-news_widget_on_main-auth',
            '{{%news_widget_on_main}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-news_widget_on_main-directory',
            '{{%news_widget_on_main}}',
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
            'fk-news_widget_on_main-auth',
            '{{%news_widget_on_main}}'
        );

        $this->dropForeignKey(
            'fk-news_widget_on_main-directory',
            '{{%news_widget_on_main}}'
        );

        $this->dropTable('{{%news_widget_on_main}}');
    }
}
