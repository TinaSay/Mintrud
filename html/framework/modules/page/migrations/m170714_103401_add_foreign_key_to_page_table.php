<?php

use yii\db\Migration;

class m170714_103401_add_foreign_key_to_page_table extends Migration
{
    public function safeUp()
    {
        // creates index for column `created_by`
        $this->createIndex(
            'idx-page-created_by',
            '{{%page}}',
            'created_by'
        );

        // add foreign key for table `{{%page}}`
        $this->addForeignKey(
            'fk-page-created_by',
            '{{%page}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL'
        );

    }

    public function safeDown()
    {
        // drops foreign key for table `{{%page}}`
        $this->dropForeignKey(
            'fk-page-created_by',
            '{{%page}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            'idx-page-created_by',
            '{{%page}}'
        );
    }
}
