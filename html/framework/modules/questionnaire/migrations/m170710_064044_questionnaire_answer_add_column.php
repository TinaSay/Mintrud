<?php

use yii\db\Migration;

class m170710_064044_questionnaire_answer_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%questionnaire_answer}}', 'position', $this->integer()->defaultValue('0')->after('title'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%questionnaire_answer}}', 'position');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170710_064044_questionnaire_answer_add_column cannot be reverted.\n";

        return false;
    }
    */
}
