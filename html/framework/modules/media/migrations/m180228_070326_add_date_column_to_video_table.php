<?php

use yii\db\Migration;

/**
 * Handles adding date to table `news`.
 */
class m180228_070326_add_date_column_to_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%video}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%video}}', 'date');
    }
}
