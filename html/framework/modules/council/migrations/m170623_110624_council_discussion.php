<?php

use yii\db\Migration;

class m170623_110624_council_discussion extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%council_discussion}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->defaultValue(''),
                'announce' => $this->string(512)->notNull()->defaultValue(''),
                'text' => $this->text(),
                'vote' =>  $this->smallInteger(1)->notNull()->defaultValue(1),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('created_by', '{{%council_discussion}}', 'created_by');

        $this->addForeignKey(
            'council_discussion_created_by_auth_id',
            '{{%council_discussion}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('council_discussion_created_by_auth_id', '{{%council_discussion}}');
        $this->dropTable('{{%council_discussion}}');

        echo "m170623_110624_council_discussion - reverted.\n";
    }

}
