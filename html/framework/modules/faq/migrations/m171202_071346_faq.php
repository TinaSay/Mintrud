<?php

use yii\db\Migration;

/**
 * Class m171202_071346_faq
 */
class m171202_071346_faq extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%faq}}',
            [
                'id' => $this->primaryKey(),
                'categoryId' => $this->integer()->null()->defaultValue(null),
                'question' => $this->string(4096)->notNull(),
                'answer' => $this->text(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'createdAt' => $this->dateTime()->null()->defaultValue(null),
                'updatedAt' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-faq-faq_category',
            '{{%faq}}',
            'categoryId',
            '{{%faq_category}}',
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
        $this->dropForeignKey(
            'fk-faq-faq_category',
            '{{%faq}}'
        );

        $this->dropTable('{{%faq}}');
    }

}
