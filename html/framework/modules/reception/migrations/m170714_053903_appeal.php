<?php

use app\modules\reception\models\Appeal;
use yii\db\Migration;

class m170714_053903_appeal extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%appeal}}', [
            'id' => $this->primaryKey(),
            'documentId' => $this->string(31)->notNull()->defaultValue(''),
            'theme' => $this->string(255)->notNull()->defaultValue(''),
            'reg_number' => $this->string(64)->notNull()->defaultValue(''),
            'type' => $this->string(31)->notNull()->defaultValue(Appeal::TYPE_EMAIL),
            'status' => $this->string(31)->notNull()->defaultValue(''),
            'comment' => $this->string(4096)->notNull()->defaultValue(''),
            'client_id' => $this->integer(11)->null()->defaultValue(null),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ], $options);

        $this->createIndex('documentId', '{{%appeal}}', ['documentId']);
        $this->createIndex('status', '{{%appeal}}', ['status']);
        $this->createIndex('reg_number', '{{%appeal}}', ['reg_number']);

        $this->createIndex('client_id', '{{%appeal}}', ['client_id']);
        $this->addForeignKey(
            'appeal_client_id_client_id',
            '{{%appeal}}',
            ['client_id'],
            '{{%client}}',
            ['id'],
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('appeal_client_id_client_id', '{{%appeal}}');
        $this->dropTable('{{%appeal}}');

        echo "m170714_053903_appeal - reverted.\n";
    }

}
