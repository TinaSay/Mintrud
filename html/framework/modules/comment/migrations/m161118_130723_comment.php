<?php

use yii\db\Migration;

class m161118_130723_comment extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(11)->null()->defaultValue(null),
            'model' => $this->string(255)->notNull()->defaultValue(''),
            'record_id' => $this->integer(11)->notNull()->defaultValue(0),
            'text' => $this->text()->null()->defaultValue(null),
            'status' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'moderated' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'language' => $this->string(8)->notNull()->defaultValue(''),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ], $options);

        $this->createIndex('parent_id', '{{%comment}}', ['parent_id']);
        $this->addForeignKey(
            'comment_parent_id_comment_id',
            '{{%comment}}',
            ['parent_id'],
            '{{%comment}}',
            ['id'],
            'CASCADE',
            'RESTRICT'
        );

        $this->createIndex('model_record_id', '{{%comment}}', ['model', 'record_id']);
        $this->createIndex('status', '{{%comment}}', ['status']);
    }

    public function safeDown()
    {
        $this->dropForeignKey('comment_parent_id_comment_id', '{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}
