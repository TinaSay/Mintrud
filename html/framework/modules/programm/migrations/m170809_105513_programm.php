<?php

use yii\db\Migration;

class m170809_105513_programm extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%programm}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(512)->notNull(),
                'text' => $this->text(),
                'url' => $this->string(24),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('ui-programm-url', '{{%programm}}', 'url', true);

        $this->addForeignKey(
            'fk-programm-created_by',
            '{{%programm}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-programm-created_by',
            '{{%programm}}'
        );

        $this->dropTable('{{%programm}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170809_105513_programm cannot be reverted.\n";

        return false;
    }
    */
}
