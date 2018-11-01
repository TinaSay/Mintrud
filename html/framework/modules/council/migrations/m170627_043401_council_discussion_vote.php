<?php

use yii\db\Migration;

class m170627_043401_council_discussion_vote extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%council_discussion_vote}}',
            [
                'id' => $this->primaryKey(),
                'council_discussion_id' => $this->integer()->null()->defaultValue(null),
                'vote' => $this->smallInteger(1)->notNull()->defaultValue(1),
                'comment' => $this->string()->notNull()->defaultValue(''),
                'council_member_id' => $this->integer()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('council_discussion_id', '{{%council_discussion_vote}}', 'council_discussion_id');

        $this->addForeignKey(
            'council_discussion_vote_cd_id_council_discussion_id',
            '{{%council_discussion_vote}}',
            'council_discussion_id',
            '{{%council_discussion}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createIndex('council_member_id', '{{%council_discussion_vote}}', 'council_member_id');

        $this->addForeignKey(
            'council_discussion_vote_member_id_council_member_id',
            '{{%council_discussion_vote}}',
            'council_member_id',
            '{{%council_member}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('council_discussion_vote_member_id_council_member_id', '{{%council_discussion_vote}}');
        $this->dropForeignKey('council_discussion_vote_cd_id_council_discussion_id', '{{%council_discussion_vote}}');

        $this->dropTable('{{%council_discussion_vote}}');

        echo "m170627_043401_council_discussion_vote - reverted.\n";
    }
}
