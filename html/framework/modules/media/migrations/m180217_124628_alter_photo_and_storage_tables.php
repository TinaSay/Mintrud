<?php

use yii\db\Migration;

/**
 * Class m180217_124628_alter_photo_and_storage_tables
 */
class m180217_124628_alter_photo_and_storage_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%photo}}', 'title', $this->text());
        $this->alterColumn('{{%storage}}', 'hint', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%photo}}', 'title', $this->string());
        $this->alterColumn('{{%storage}}', 'hint', $this->string());
    }


}
