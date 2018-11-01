<?php

use yii\db\Migration;

class m170711_095128_tag_relation extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%tag_relation}}',
            [
                'id' => $this->primaryKey(),
                'tag_id' => $this->integer()->notNull(),
                'record_id' => $this->integer()->notNull(),
                'model' => $this->string(256)->notNull(),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('iu-tag_id-record_id-model', '{{%tag_relation}}', ['tag_id', 'record_id', 'model'], true);

        $this->addForeignKey(
            'fk-tag_relation-tag',
            '{{%tag_relation}}',
            'tag_id',
            '{{%tag}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-tag_relation-auth',
            '{{%tag_relation}}',
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
            'fk-tag_relation-auth',
            '{{%tag_relation}}'
        );

        $this->dropForeignKey(
            'fk-tag_relation-tag',
            '{{%tag_relation}}'
        );

        $this->dropTable('{{%tag_relation}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170711_095128_tag_relation cannot be reverted.\n";

        return false;
    }
    */
}
