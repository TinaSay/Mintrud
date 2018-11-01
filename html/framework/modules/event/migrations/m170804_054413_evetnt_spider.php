<?php

use yii\db\Migration;

class m170804_054413_evetnt_spider extends Migration
{
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%event_spider}}',
            [
                'id' => $this->primaryKey(),
                'url' => $this->string(512)->notNull(),
                'is_parsed' => $this->smallInteger(1)->notNull()->defaultValue('0'),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('ui-url', '{{%event_spider}}', 'url', true);

    }

    public function safeDown()
    {
        $this->dropTable('{{%event_spider}}');
    }

}
