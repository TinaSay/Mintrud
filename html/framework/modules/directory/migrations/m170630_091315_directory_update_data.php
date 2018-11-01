<?php

use yii\db\Migration;

class m170630_091315_directory_update_data extends Migration
{
    public function safeUp()
    {
        $directories = (new \yii\db\Query())->from('{{%directory}}')->orderBy(['id' => SORT_ASC])->indexBy('id')->all();

        foreach ($directories as &$directory) {
            if (!is_null($directory['parent_id'])) {
                $depth = $directories[$directory['parent_id']]['depth'] + 1;
                $directory['depth'] = $depth;
                $this->update('{{%directory}}', ['depth' => $directory['depth']], ['id' => $directory['id']]);
            }
        }
    }

    public function safeDown()
    {
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170630_091315_directroy_update_data cannot be reverted.\n";

        return false;
    }
    */
}
