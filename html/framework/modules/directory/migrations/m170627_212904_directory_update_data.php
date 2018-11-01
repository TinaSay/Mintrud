<?php

use yii\db\Expression;
use yii\db\Migration;

class m170627_212904_directory_update_data extends Migration
{
    public function safeUp()
    {
        $this->update(
            '{{%directory}}',
            [
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ]
        );
    }

    public function safeDown()
    {

    }
}
