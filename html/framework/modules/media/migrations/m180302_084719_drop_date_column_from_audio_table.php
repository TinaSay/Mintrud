<?php

use yii\db\Migration;

/**
 * Handles dropping date from table `video`.
 */
class m180302_084719_drop_date_column_from_audio_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%audio}}', 'date');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('{{%audio}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }
}
