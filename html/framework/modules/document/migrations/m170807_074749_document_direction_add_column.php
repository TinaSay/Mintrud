<?php

use yii\db\Migration;

class m170807_074749_document_direction_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document_direction}}', 'directory_id', $this->integer()->after('title'));

        $this->addForeignKey(
            'fk-document_direction-directory',
            '{{%document_direction}}',
            'directory_id',
            '{{%directory}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-document_direction-directory',
            '{{%document_direction}}'
        );

        $this->dropColumn(
            '{{%document_direction}}',
            'directory_id'
        );
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170807_074749_document_direction_add_column cannot be reverted.\n";

        return false;
    }
    */
}
