<?php

use yii\db\Migration;

class m170913_124006_tenders extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%tender}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(1024)->notNull(),
                'regNumber' => $this->string(31)->notNull(),
                'orderIdentity' => $this->string(63)->notNull()->defaultValue(''),
                'auction' => $this->string(512)->notNull()->defaultValue(''),
                'orderSum' => $this->float()->notNull()->defaultValue(0),
                'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'createdAt' => $this->dateTime()->null()->defaultValue(null),
                'updatedAt' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('regNumber', '{{%tender}}', ['regNumber']);
        $this->createIndex('hidden', '{{%tender}}', ['hidden']);

    }

    public function safeDown()
    {
        $this->dropTable('{{%tender}}');
    }
}
