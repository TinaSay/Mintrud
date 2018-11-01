<?php

use yii\db\Migration;

class m170810_071946_govserv extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() === 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%govserv}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(512)->notNull(),
                'text' => $this->text(),
                'url' => $this->string(24),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('ui-govserv-url', '{{%govserv}}', 'url', true);

        $this->addForeignKey(
            'fk-govserv-created_by',
            '{{%govserv}}',
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
            'fk-govserv-created_by',
            '{{%govserv}}'
        );

        $this->dropTable('{{%govserv}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170810_071946_govserv cannot be reverted.\n";

        return false;
    }
    */
}
