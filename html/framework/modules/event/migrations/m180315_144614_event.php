<?php

use yii\db\Migration;

/**
 * Class m180315_144614_event
 */
class m180315_144614_event extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->update('{{%event}}', ['[[show_form]]' => 0]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180315_144614_event cannot be reverted.\n";

        return true;
    }
}
