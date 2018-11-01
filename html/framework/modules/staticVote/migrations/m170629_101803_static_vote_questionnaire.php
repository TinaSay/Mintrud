<?php

use yii\db\Migration;

class m170629_101803_static_vote_questionnaire extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable(
            '{{%static_vote_questionnaire}}',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull()->defaultValue(''),
                'hidden' => $this->smallInteger()->notNull()->defaultValue(0),
                'created_at' => $this->dateTime()->null()->defaultValue(null),
                'updated_at' => $this->dateTime()->null()->defaultValue(null),
            ],
            $options
        );
    }

    public function safeDown()
    {
        echo "m170629_101803_static_vote_questionnaire - reverted.\n";
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170629_101803_static_vote_questiionaire cannot be reverted.\n";

        return false;
    }
    */
}
