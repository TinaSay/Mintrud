<?php

use yii\db\Migration;

/**
 * Handles adding url to table `storage`.
 */
class m180402_110037_add_url_column_to_storage_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('{{%storage}}', 'url', $this->string()->null());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('{{%storage}}', 'url');
    }
}
