<?php

use yii\db\Migration;

class m170707_101035_questionnaire_result extends Migration
{
    public function safeUp()
    {
        $options = ($this->db->getDriverName() === 'mysql') ? 'ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci' : null;

        $this->createTable('{{%questionnaire_result}}', [
            'id' => $this->primaryKey(),
            'questionnaire_id' => $this->integer(),
            'ip' => $this->bigInteger()->notNull()->defaultValue('0'),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ],
            $options
        );

        $this->createIndex('ip', '{{%questionnaire_result}}', ['ip']);

        $this->addForeignKey(
            'fk-questionnaire_result-questionnaire',
            '{{%questionnaire_result}}',
            'questionnaire_id',
            '{{%questionnaire}}',
            'id',
            'RESTRICT',
            'RESTRICT'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-questionnaire_result-questionnaire',
            '{{%questionnaire_result}}'
        );

        $this->dropTable('{{%questionnaire_result}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170707_101035_questionnaire_result cannot be reverted.\n";

        return false;
    }
    */
}
