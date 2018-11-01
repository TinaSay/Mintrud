<?php

use yii\db\Migration;

/**
 * Handles adding show_form to table `event`.
 */
class m180314_095506_add_show_form_column_to_event_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%event}}', 'show_form', $this->smallInteger()->defaultValue(1)->notNull());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%event}}', 'show_form');
    }
}
