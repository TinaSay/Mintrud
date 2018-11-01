<?php

use yii\db\Migration;

class m170628_064153_widget_on_main extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%doc_widget_on_main}}',
            [
                'id' => $this->primaryKey(),
                'directory_id' => $this->integer()->notNull(),
                'title' => $this->string(256),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%doc_widget_on_main}}', 'hidden');
        $this->createIndex('i-created_by', '{{%doc_widget_on_main}}', 'created_by');

        $this->addForeignKey(
            'fk-doc_widget_on_main-directory',
            '{{%doc_widget_on_main}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-doc_widget_om_main-auth',
            '{{%doc_widget_on_main}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-doc_widget_om_main-auth',
            '{{%doc_widget_on_main}}'
        );

        $this->dropForeignKey(
            'fk-doc_widget_on_main-directory',
            '{{%doc_widget_on_main}}'
        );

        $this->dropTable('{{%doc_widget_on_main}}');
    }

}
