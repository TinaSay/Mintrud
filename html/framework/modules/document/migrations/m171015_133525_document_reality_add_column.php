<?php

use yii\db\Migration;

class m171015_133525_document_reality_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'reality', $this->smallInteger()->notNull()->defaultValue(1)->after('type_document_id'));
        $this->addColumn('{{%document}}', 'old_document_id', $this->integer()->after('reality'));

        $this->addForeignKey(
            'fk-document-old_document',
            '{{%document}}',
            'old_document_id',
            '{{%document}}',
            'id',
            'SET NULL',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'reality');
        $this->dropColumn('{{%document}}', 'old_document_id');
    }

}
