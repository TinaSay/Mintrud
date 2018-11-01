<?php

use yii\db\Migration;

/**
 * Handles dropping date from table `video`.
 */
class m180302_084718_drop_date_column_from_video_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%video}}', 'date');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('{{%video}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }
}
