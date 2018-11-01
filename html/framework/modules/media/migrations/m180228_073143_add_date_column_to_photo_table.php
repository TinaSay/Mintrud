<?php

use yii\db\Migration;

/**
 * Handles adding date to table `photo`.
 */
class m180228_073143_add_date_column_to_photo_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%photo}}', 'date', $this->dateTime()->null()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%photo}}', 'date');
    }
}
