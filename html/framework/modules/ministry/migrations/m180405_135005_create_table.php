<?php

use yii\db\Migration;

/**
 * Class m180405_135005_create_table
 */
class m180405_135005_create_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        $this->createTable('{{%ministry_assignment}}', [
            'id' => $this->primaryKey(),
            'auth_id' => $this->integer(11)->notNull(),
            'ministry_id' => $this->integer(11)->notNull(),
        ], $options);

        $this->addForeignKey(
            'ministry_assignment_auth_id_auth_id',
            '{{%ministry_assignment}}',
            'auth_id',
            '{{%auth}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->addForeignKey(
            'ministry_assignment_ministry_id_ministry_id',
            '{{%ministry_assignment}}',
            'ministry_id',
            '{{%ministry}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('ministry_assignment_ministry_id_ministry_id', '{{%ministry_assignment}}');
        $this->dropForeignKey('ministry_assignment_auth_id_auth_id', '{{%ministry_assignment}}');
        $this->dropTable('{{%ministry_assignment}}');
    }
}
