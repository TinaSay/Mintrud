<?php

use yii\db\Migration;

/**
 * Class m180428_090823_appeal_file_size
 */
class m180428_090823_appeal_file_size extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%appeal_files}}', 'size',
            $this->integer()->null()->after('src')
        );

    }

    public function safeDown()
    {
        $this->dropColumn('{{%appeal_files}}', 'size');
    }
}
