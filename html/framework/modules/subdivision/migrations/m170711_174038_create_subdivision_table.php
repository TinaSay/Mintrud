<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%subdivision}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%subdivision}}`
 */
class m170711_174038_create_subdivision_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%subdivision}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'fragment' => $this->string()->notNull(),
            'position' => $this->integer()->notNull()->defaultValue(0),
            'depth' => $this->integer()->notNull()->defaultValue(0),
            'hidden' => $this->smallinteger(1)->notNull()->defaultvalue(0),
            'created_by' => $this->integer(),
            'language' => $this->string(8),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ],
            $options
        );

        // creates index for column `parent_id`
        $this->createIndex(
            'idx-subdivision-parent_id',
            '{{%subdivision}}',
            'parent_id'
        );

        // add foreign key for table `{{%subdivision}}`
        $this->addForeignKey(
            'fk-subdivision-parent_id',
            '{{%subdivision}}',
            'parent_id',
            '{{%subdivision}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `{{%subdivision}}`
        $this->dropForeignKey(
            'fk-subdivision-parent_id',
            '{{%subdivision}}'
        );

        // drops index for column `parent_id`
        $this->dropIndex(
            'idx-subdivision-parent_id',
            '{{%subdivision}}'
        );

        $this->dropTable('{{%subdivision}}');
    }
}
