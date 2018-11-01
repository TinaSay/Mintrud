<?php

use yii\db\Migration;

class m170615_080013_event extends Migration
{

    public function up()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%event}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(256)->notNull()->defaultValue(''),
                'text' => $this->text()->notNull(),
                'src' => $this->string(256),
                'date' => $this->date()->null()->defaultValue(null),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(1),
                'created_by' => $this->integer(),
                'language' => $this->string(8)->null()->defaultValue(null),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->createIndex('i-hidden', '{{%event}}', 'hidden');
        $this->createIndex('i-language', '{{%event}}', 'language');

        $this->addForeignKey(
            'fk-event-auth',
            '{{%event}}',
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function down()
    {
        $this->dropForeignKey(
            'fk-event-auth',
            '{{%event}}'
        );
        $this->dropTable('{{%event}}');
    }
}
