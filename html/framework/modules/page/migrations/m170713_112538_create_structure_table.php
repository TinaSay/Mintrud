<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%structure}}`.
 */
class m170713_112538_create_structure_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%structure}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'text' => $this->text(),
            'hidden' => $this->smallinteger(1)->notNull()->defaultvalue(0),
            'created_by' => $this->integer(),
            'created_at' => $this->datetime(),
            'updated_at' => $this->datetime(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%structure}}');
    }
}
