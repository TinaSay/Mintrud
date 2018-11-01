<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%video}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%auth}}`
 */
class m170726_164122_create_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%video}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text(),
            'src' => $this->string()->notNull(),
            'language' => $this->string(8),
            'hidden' => $this->smallInteger(1)->notNull()->defaultvalue(0),
            'created_by' => $this->integer(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ], $options);

        // creates index for column `created_by`
        $this->createIndex(
            'idx-video-created_by',
            '{{%video}}',
            'created_by'
        );

        // add foreign key for table `{{%auth}}`
        $this->addForeignKey(
            'fk-video-created_by',
            '{{%video}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%auth}}`
        $this->dropForeignKey(
            'fk-video-created_by',
            '{{%video}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-video-created_by',
            '{{%video}}'
        );

        $this->dropTable('{{%video}}');
    }
}
