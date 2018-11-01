<?php

use yii\db\Migration;

class m170714_102009_add_foreign_key_to_subdivision_table extends Migration
{
    public function safeUp()
    {
        // creates index for column `created_by`
        $this->createIndex(
            'idx-subdivision-created_by',
            '{{%subdivision}}',
            'created_by'
        );

        // add foreign key for table `{{%subdivision}}`
        $this->addForeignKey(
            'fk-subdivision-created_by',
            '{{%subdivision}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        // drops foreign key for table `{{%subdivision}}`
        $this->dropForeignKey(
            'fk-subdivision-created_by',
            '{{%subdivision}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-subdivision-created_by',
            '{{%subdivision}}'
        );
    }
}
