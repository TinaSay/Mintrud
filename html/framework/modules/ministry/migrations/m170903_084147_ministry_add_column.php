<?php

use yii\db\Migration;

class m170903_084147_ministry_add_column extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%ministry}}', 'language', $this->string(8)->after('position'));

        $this->update(
            '{{%ministry}}',
            [
                'language' => 'ru-RU',
            ]
        );

        $this->alterColumn('{{%ministry}}', 'language', $this->string(8)->notNull());
    }

    public function safeDown()
    {
        $this->dropColumn('{{%ministry}}', 'language');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170903_084147_ministry_add_column cannot be reverted.\n";

        return false;
    }
    */
}
