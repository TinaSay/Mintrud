<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%subdivision}}`
 */
class m170712_072952_create_page_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string()->notNull(),
            'last_name' => $this->string()->notNull(),
            'middle_name' => $this->string()->notNull(),
            'alias' => $this->string()->unique()->notNull(),
            'text' => $this->text(),
            'subdivision_id' => $this->integer()->notNull(),
            'hidden' => $this->smallinteger(1)->notNull()->defaultvalue(0),
            'created_by' => $this->integer(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);

        // creates index for column `subdivision_id`
        $this->createIndex(
            'idx-page-subdivision_id',
            '{{%page}}',
            'subdivision_id'
        );

        // creates index for column `alias`
        $this->createIndex(
            'idx-page-alias',
            '{{%page}}',
            'alias'
        );

        // add foreign key for table `{{%subdivision}}`
        $this->addForeignKey(
            'fk-page-subdivision_id',
            '{{%page}}',
            'subdivision_id',
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
        $this->dropForeignKey('fk-page-subdivision_id', '{{%page}}');
        // drops index for column `subdivision_id`
        $this->dropIndex('idx-page-subdivision_id', '{{%page}}');
        $this->dropIndex('idx-page-alias', '{{%page}}');
        $this->dropTable('{{%page}}');
    }
}
