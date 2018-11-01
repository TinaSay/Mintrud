<?php

use yii\db\Migration;

class m170824_100738_media_audio_add_alter extends Migration
{
    public function safeUp()
    {
        $this->alterColumn('{{%audio}}', 'src', $this->string(255));
        $this->addColumn('{{%audio}}', 'show_on_main', $this->smallInteger(1)->notNull()->defaultValue(0)->after('src'));
    }

    public function safeDown()
    {
        $this->alterColumn('{{%audio}}', 'src', $this->string(255)->notNull());
        $this->dropColumn('{{%audio}}', 'show_on_main');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170824_100738_media_audio_add_alter cannot be reverted.\n";

        return false;
    }
    */
}
