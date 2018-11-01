<?php

use yii\db\Migration;

class m170912_060926_meeting extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%council_meeting}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'date' => $this->date()->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('created_by', '{{%council_meeting}}', 'created_by');

        $this->addForeignKey(
            'council_meeting_created_by_auth_id',
            '{{%council_meeting}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addColumn('{{%council_discussion}}', 'meeting_id', $this->integer()->null()->defaultValue(null)
            ->after('id'));
        $this->createIndex('meeting_id', '{{%council_discussion}}', 'meeting_id');

        $this->addForeignKey(
            'council_discussion_meeting_id_council_meeting_id',
            '{{%council_discussion}}',
            'meeting_id',
            '{{%council_meeting}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

    }

    public function safeDown()
    {
        $this->dropForeignKey('council_discussion_meeting_id_council_meeting_id',
            '{{%council_discussion}}');

        $this->dropForeignKey('council_meeting_created_by_auth_id',
            '{{%council_meeting}}');

        $this->dropColumn('{{%council_discussion}}', 'meeting_id');

        $this->dropTable('{{%council_meeting}}');

        echo "m170912_060926_meeting - reverted.\n";

    }

}
