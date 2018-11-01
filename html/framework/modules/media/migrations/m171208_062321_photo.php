<?php

use yii\db\Migration;

/**
 * Class m171208_062321_photo
 */
class m171208_062321_photo extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'show_on_main' => $this->smallInteger(1)->notNull()->defaultValue(0),
            'language' => $this->string(8),
            'hidden' => $this->smallInteger(1)->notNull()->defaultvalue(0),
            'created_by' => $this->integer(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ], $options);

        $this->createIndex(
            'idx-photo-show_on_main',
            '{{%photo}}',
            'created_by'
        );

        // creates index for column `created_by`
        $this->createIndex(
            'idx-photo-created_by',
            '{{%photo}}',
            'created_by'
        );

        // add foreign key for table `{{%auth}}`
        $this->addForeignKey(
            'fk-photo-created_by',
            '{{%photo}}',
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
            'fk-photo-created_by',
            '{{%photo}}'
        );

        $this->dropTable('{{%photo}}');
    }
}
