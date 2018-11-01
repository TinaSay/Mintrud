<?php

use app\modules\directory\rules\type\TypeInterface;
use yii\db\Migration;

class m170829_061818_directory_add_data extends Migration
{
    public function safeUp()
    {
        $this->insert(
            '{{%directory}}',
            [
                'type' => TypeInterface::TYPE_NEWS,
                'title' => 'News',
                'fragment' => 'eng/news',
                'url' => 'eng/news',
                'language' => 'en-EN',
                'created_at' => new \yii\db\Expression('NOW()'),
                'updated_at' => new \yii\db\Expression('NOW()'),
            ]
        );
    }

    public function safeDown()
    {
        $this->delete('{{%directory}}', ['url' => 'eng/news']);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170829_061818_directory_add_data cannot be reverted.\n";

        return false;
    }
    */
}
