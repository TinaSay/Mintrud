<?php

use yii\db\Migration;

/**
 * Class m171202_071343_faq_category
 */
class m171202_071343_faq_category extends Migration
{
    /**
     * @inheritfaq_category
     */
    public function safeUp()
    {
        $options = $this->db->getDriverName() == 'mysql' ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%faq_category}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'hidden' => $this->smallInteger(1)->notNull()->defaultValue(0),
                'createdBy' => $this->integer(),
                'language' => $this->string(8),
                'createdAt' => $this->dateTime()->null()->defaultValue(null),
                'updatedAt' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );

        $this->addForeignKey(
            'fk-faq_category-auth',
            '{{%faq_category}}',
            'createdBy',
            '{{%auth}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );


    }

    /**
     * @inheritfaq_category
     */
    public function safeDown()
    {

        $this->dropForeignKey(
            'fk-faq_category-auth',
            '{{%faq_category}}'
        );

        $this->dropTable('{{%faq_category}}');
    }

}
