<?php

use yii\db\Migration;

/**
 * Handles adding sortOrder to table `storage`.
 */
class m180423_131413_add_sortOrder_column_to_storage_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%storage}}', 'sortOrder', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%storage}}', 'sortOrder');
    }
}
