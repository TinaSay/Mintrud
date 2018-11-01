<?php

use yii\db\Migration;

/**
 * Class m180103_115841_banner
 */
class m180103_115841_banner extends Migration
{
    /** @var string  */
    private $tableName = '{{%banner}}';
    
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') 
            ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;
        
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'title' => $this->string(64)->notNull()->defaultValue(''),
            'url' => $this->string(256)->notNull(),
            'hidden' => $this->smallInteger(1)->notNull()->defaultValue('0'),
            'position' => $this->integer()->notNull()->defaultValue('0'),
            'created_at' => $this->dateTime()->null()->defaultValue(null),
            'updated_at' => $this->dateTime()->null()->defaultValue(null),
        ],
            $options
        );

        $this->createIndex('i-position', $this->tableName, ['position']);
        $this->createIndex('i-hidden', $this->tableName, ['hidden']);
        $this->createIndex('i-url', $this->tableName, 'url');

        $this->addForeignKey(
            'fk-banner-banner_category',
            $this->tableName,
            'category_id',
            '{{%banner_category}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-banner-banner_category', $this->tableName);
        $this->dropTable($this->tableName);
    }
}
