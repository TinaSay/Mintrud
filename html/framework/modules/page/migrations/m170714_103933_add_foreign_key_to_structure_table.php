<?php

use yii\db\Migration;

class m170714_103933_add_foreign_key_to_structure_table extends Migration
{
    public function safeUp()
    {
        // creates index for column `created_by`
        $this->createIndex(
            'idx-structure-created_by',
            '{{%structure}}',
            'created_by'
        );

        // add foreign key for table `{{%structure}}`
        $this->addForeignKey(
            'fk-structure-created_by',
            '{{%structure}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        // drops foreign key for table `{{%structure}}`
        $this->dropForeignKey(
            'fk-structure-created_by',
            '{{%structure}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-structure-created_by',
            '{{%structure}}'
        );
    }
}
