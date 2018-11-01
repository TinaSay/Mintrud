<?php

use yii\db\Migration;

class m170811_061046_anticorruption extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%anticorruption}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(524)->notNull(),
                'text' => $this->text(),
                'url' => $this->string(24),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('ui-anticorruption-url', '{{%anticorruption}}', 'url', true);

        $this->addForeignKey(
            'fk-anticorruption-created_by',
            '{{%anticorruption}}',
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
            'fk-anticorruption-created_by',
            '{{%anticorruption}}'
        );

        $this->dropTable('{{%anticorruption}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170811_061046_anticorruption cannot be reverted.\n";

        return false;
    }
    */
}
