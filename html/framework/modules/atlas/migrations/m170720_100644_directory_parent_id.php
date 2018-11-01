<?php

use yii\db\Migration;

class m170720_100644_directory_parent_id extends Migration
{
    public function safeUp()
    {
        $this->addColumn(
            '{{%atlas_directory}}', 'parent_id', $this->integer()->after('id'));
        $this->addColumn(
            '{{%atlas_directory}}', 'depth',
            $this->integer()->notNull()->defaultValue('0')->after('position')
        );

        $this->addForeignKey(
            'fk-atlas_directory_parent_id-atlas_directory_id',
            '{{%atlas_directory}}',
            'parent_id',
            '{{%atlas_directory}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-atlas_directory_parent_id-atlas_directory_id', '{{%atlas_directory}}');
        $this->dropColumn('{{%atlas_directory}}', 'parent_id');
        $this->dropColumn('{{%atlas_directory}}', 'depth');

        echo "m170720_100644_directory_parent_id - reverted.\n";

        return true;
    }

}
