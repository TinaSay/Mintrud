<?php

use yii\db\Migration;

class m170707_100729_questionnaire extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%questionnaire}}', [
            'id' => $this->primaryKey(),
            'directory_id' => $this->integer(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull()->defaultValue(''),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
            'created_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ],
            $options
        );


        $this->createIndex('i-hidden', '{{%questionnaire}}', ['hidden']);
        $this->createIndex('i-created_by', '{{%questionnaire}}', ['created_by']);
        $this->addForeignKey(
            'fk-questionnaire-auth',
            '{{%questionnaire}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-questionnaire-directory',
            '{{%questionnaire}}',
            'directory_id',
            '{{%directory}}',
            'ID',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire-auth',
            '{{%questionnaire}}'
        );
        $this->dropForeignKey(
            'fk-questionnaire-directory',
            '{{%questionnaire}}'
        );
        $this->dropTable('{{%questionnaire}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_100729_questionnaire cannot be reverted.\n";

        return false;
    }
    */
}
