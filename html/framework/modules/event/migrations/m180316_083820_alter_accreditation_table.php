<?php

use yii\db\Migration;

/**
 * Class m180316_083820_alter_accreditation_table
 */
class m180316_083820_alter_accreditation_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%accreditation}}', 'accid', $this->string());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%accreditation}}', 'accid', $this->string()->notNull());
    }
}
