<?php

use yii\db\Migration;

class m170624_100741_comment_council_member extends Migration
{
    public function safeUp()
    {
        $table = Yii::$app->db->schema->getTableSchema('{{%comment}}');
        if (!isset($table->columns['council_member_id'])) {
            $this->addColumn('{{%comment}}', 'council_member_id',
                $this->integer(11)->null()->defaultValue(null)->after('[[language]]'));

            $this->createIndex('council_member_id', '{{%comment}}', ['council_member_id']);

            $this->addForeignKey(
                'comment_council_member_id_oauth_council_member_id',
                '{{%comment}}',
                'council_member_id',
                '{{%council_member}}',
                'id',
                'SET NULL',
                'RESTRICT'
            );
        }

    }

    public function safeDown()
    {
        $this->dropForeignKey('comment_council_member_id_oauth_council_member_id', '{{%comment}}');
        $this->dropColumn('{{%comment}}', 'council_member_id');
    }
}
