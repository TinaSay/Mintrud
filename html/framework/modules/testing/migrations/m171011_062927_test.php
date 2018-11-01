<?php

use yii\db\Migration;

class m171011_062927_test extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%testing}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'timer' => $this->integer(),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
            'createdBy' => $this->integer(),
            'createdAt' => $this->dateTime()->null()->defaultValue(null),
            'updatedAt' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );


        $this->createIndex('hidden', '{{%testing}}', ['hidden']);
        $this->createIndex('createdBy', '{{%testing}}', ['createdBy']);
        $this->addForeignKey(
            'testing_auth',
            '{{%testing}}',
            'createdBy',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('testing_auth', '{{%testing}}');
        $this->dropTable('{{%testing}}');
    }

}
