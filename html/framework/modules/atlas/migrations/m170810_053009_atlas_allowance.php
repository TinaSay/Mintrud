<?php

use yii\db\Migration;

class m170810_053009_atlas_allowance extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%atlas_allowance}}',
            [
                'id' => $this->primaryKey(),
                'directory_subject_id' => $this->integer()->null()->defaultValue(null),
                'directory_allowance_id' => $this->integer()->null()->defaultValue(null),
                'federal' => $this->text(),
                'regional' => $this->text(),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );


        $this->addForeignKey(
            'fk-atlas_allowance_directory_subject_id-directory_subject_id',
            '{{%atlas_allowance}}',
            'directory_subject_id',
            '{{%atlas_directory}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-atlas_allowance_directory_allowance_id-directory_allowance_id',
            '{{%atlas_allowance}}',
            'directory_allowance_id',
            '{{%atlas_directory}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-atlas_allowance_directory_subject_id-directory_subject_id',
            '{{%atlas_allowance}}'
        );

        $this->dropForeignKey(
            'fk-atlas_allowance_directory_allowance_id-directory_allowance_id',
            '{{%atlas_allowance}}'
        );

        $this->dropTable('{{%atlas_allowance}}');
    }
}
