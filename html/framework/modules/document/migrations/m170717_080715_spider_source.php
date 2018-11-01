<?php

use yii\db\Migration;

class m170717_080715_spider_source extends Migration
{

    public function up()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%spider}}',
            [
                'id' => $this->primaryKey(),
                'original' => $this->string(1024)->notNull(),
                'is_parsed' => $this->smallInteger()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime(),
                'updated_at' => $this->dateTime(),
            ],
            $options
        );

        $this->createIndex('iu-original', '{{%spider}}', ['original'], true);
    }

    public function down()
    {
        $this->dropTable('{{%spider}}');
    }

}
