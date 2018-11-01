<?php

use yii\db\Migration;

class m170816_090053_ministry_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%ministry}}', 'directory_id', $this->integer()->after('parent_id'));

        $this->addForeignKey(
            'fk-ministry-directory_id',
            '{{%ministry}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-ministry-directory_id',
            '{{%ministry}}'
        );

        $this->dropColumn('{{%ministry}}', 'directory_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170816_090053_ministry_add_column cannot be reverted.\n";

        return false;
    }
    */
}
