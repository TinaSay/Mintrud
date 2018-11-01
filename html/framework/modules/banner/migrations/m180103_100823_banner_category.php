<?php

use yii\db\Migration;

/**
 * Class m180103_100823_banner_category
 */
class m180103_100823_banner_category extends Migration
{
    /**
     * @var string
     */
    private $tableName = '{{%banner_category}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql')
            ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull()->defaultValue(''),
            'created_by' => $this->integer()->null(),
            'language' => $this->string(8)->null()->defaultValue(null),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );

        $this->addForeignKey(
            'fk-banner_category-auth',
            $this->tableName,
            'created_by',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-banner_category-auth', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
