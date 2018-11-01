<?php

use yii\db\Migration;

/**
 * Handles dropping date from table `video`.
 */
class m180302_084720_drop_date_column_from_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->dropColumn('{{%photo}}', 'date');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->addColumn('{{%photo}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }
}
