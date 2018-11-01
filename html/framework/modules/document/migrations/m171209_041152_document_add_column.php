<?php

use yii\db\Migration;

/**
 * Class m171209_041152_document_add_column
 */
class m171209_041152_document_add_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('{{%document}}', 'ministry_date', $this->date()->after('ministry_number'));
        $this->addColumn('{{%document}}', 'note', $this->text()->after('ministry_date'));
        $this->addColumn('{{%document}}', 'officially_published', $this->text()->after('note'));
        $this->addColumn('{{%document}}', 'link', $this->text()->after('officially_published'));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%document}}', 'ministry_date');
        $this->dropColumn('{{%document}}', 'note');
        $this->dropColumn('{{%document}}', 'officially_published');
        $this->dropColumn('{{%document}}', 'link');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171209_041152_document_add_column cannot be reverted.\n";

        return false;
    }
    */
}
