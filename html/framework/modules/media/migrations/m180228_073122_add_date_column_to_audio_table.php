<?php

use yii\db\Migration;

/**
 * Handles adding date to table `audio`.
 */
class m180228_073122_add_date_column_to_audio_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%audio}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%audio}}', 'date');
    }
}
