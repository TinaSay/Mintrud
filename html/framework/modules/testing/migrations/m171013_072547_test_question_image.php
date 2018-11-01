<?php

use yii\db\Migration;

class m171013_072547_test_question_image extends Migration
{
    public function safeUp()
    {
        $this->dropForeignKey('testing_result_test_q_id_testing_question_id', '{{%testing_result}}');
        $this->dropColumn('{{%testing_result}}', 'testQuestionId');
        $this->addColumn('{{%testing_question}}', 'src',
            $this->string(64)->notNull()->defaultValue('')->after('title')
        );
    }

    public function safeDown()
    {
        $this->dropColumn('{{%testing_question}}', 'src');
    }

}
