<?php

use yii\db\Migration;

class m170712_114942_organ extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%organ}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(256)->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-title', '{{%organ}}', 'title');
        $this->createIndex('i-hidden', '{{%organ}}', 'hidden');

        $this->addForeignKey(
            'fk-organ-auth',
            '{{%organ}}',
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
            'fk-organ-auth',
            '{{%organ}}'
        );

        $this->dropTable('{{%organ}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_114942_organ cannot be reverted.\n";

        return false;
    }
    */
}
