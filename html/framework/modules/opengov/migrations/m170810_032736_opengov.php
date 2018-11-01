<?php

use yii\db\Migration;

class m170810_032736_opengov extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%opengov}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(512)->notNull(),
                'text' => $this->text(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-opengov-created_by',
            '{{%opengov}}',
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
            'fk-opengov-created_by',
            '{{%opengov}}'
        );

        $this->dropTable('{{%opengov}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170810_032736_opengov cannot be reverted.\n";

        return false;
    }
    */
}
