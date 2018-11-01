<?php

use yii\db\Migration;

class m170712_095430_type_document extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%type_document}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(256)->notNull(),
                'hidden' => $this->integer()->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('i-title', '{{%type_document}}', 'title');
        $this->createIndex('i-hidden', '{{%type_document}}', 'hidden');

        $this->addForeignKey(
            'fk-type_document-auth',
            '{{%type_document}}',
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
            'fk-type_document-auth',
            '{{%type_document}}'
        );

        $this->dropTable('{{%type_document}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170712_095430_type_document cannot be reverted.\n";

        return false;
    }
    */
}
