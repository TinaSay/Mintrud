<?php

use yii\db\Migration;

/**
 * Class m171208_115236_photo_news
 */
class m171208_115236_photo_news extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        $this->createTable(
            '{{%photo_link}}',
            [
                'id' => $this->primaryKey(),
                'photoId' => $this->integer(11)->defaultValue(0),
                'model' => $this->string(128)->notNull(),
                'recordId' => $this->integer()->notNull(),
            ],
            $options
        );
        $this->createIndex('i-model-record', '{{%photo_link}}', ['model', 'recordId']);

        $this->addForeignKey(
            'fk-photo_link-photo',
            '{{%photo_link}}',
            'photoId',
            '{{%photo}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-photo_link-photo', '{{%photo_link}}');
        $this->dropTable('{{%photo_link}}');
    }
}
