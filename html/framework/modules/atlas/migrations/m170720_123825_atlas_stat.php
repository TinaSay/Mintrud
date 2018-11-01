<?php

use yii\db\Migration;

class m170720_123825_atlas_stat extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%atlas_stat}}',
            [
                'id' => $this->primaryKey(),
                'directory_subject_id' => $this->integer()->null()->defaultValue(null),
                'directory_rate_id' => $this->integer()->null()->defaultValue(null),
                'year' => $this->string(64)->notNull()->defaultValue(''),
                'value' => $this->float(1)->notNull()->defaultValue('0'),
                'created_by' => $this->integer(),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('i-year', '{{%atlas_stat}}', ['year']);
        $this->createIndex('i-value', '{{%atlas_stat}}', ['value']);


        $this->addForeignKey(
            'fk-atlas_stat_directory_subject_id-directory_subject_id',
            '{{%atlas_stat}}',
            'directory_subject_id',
            '{{%atlas_directory}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );

        $this->addForeignKey(
            'fk-atlas_stat_directory_rate_id-directory_rate_id',
            '{{%atlas_stat}}',
            'directory_rate_id',
            '{{%atlas_directory}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-atlas_stat_directory_subject_id-directory_subject_id',
            '{{%atlas_stat}}'
        );

        $this->dropForeignKey(
            'fk-atlas_stat_directory_rate_id-directory_rate_id',
            '{{%atlas_stat}}'
        );

        $this->dropTable('{{%atlas_stat}}');
    }
}
