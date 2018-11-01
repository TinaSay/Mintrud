<?php

use yii\db\Migration;

class m170623_103352_council_member_log extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%council_member_log}}',
            [
                'id' => $this->primaryKey(),
                'council_member_id' => $this->integer(11)->null()->defaultValue(null),
                'status' => $this->smallInteger(1)->null()->defaultValue(null),
                'ip' => $this->bigInteger(20)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('council_member_id', '{{%council_member_log}}', ['council_member_id']);
        $this->addForeignKey(
            'council_member_log_council_member_id_council_member_id',
            '{{%council_member_log}}',
            ['council_member_id'],
            '{{%council_member}}',
            ['id'],
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('council_member_log_council_member_id_council_member_id', '{{%council_member_log}}');
        $this->dropTable('{{%council_member_log}}');
    }
}
