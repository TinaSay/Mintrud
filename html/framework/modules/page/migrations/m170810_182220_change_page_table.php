<?php

use yii\db\Migration;

class m170810_182220_change_page_table extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%page}}', 'title', $this->string());
        $this->update('{{%page}}',
            ['title' => new \yii\db\Expression('CONCAT_WS(" ", last_name, first_name, middle_name)')]);
        $this->dropColumn('{{%page}}', 'last_name');
        $this->dropColumn('{{%page}}', 'first_name');
        $this->dropColumn('{{%page}}', 'middle_name');
    }

    public function safeDown()
    {
        $this->addColumn('{{%page}}', 'first_name', $this->string());
        $this->addColumn('{{%page}}', 'last_name', $this->string());
        $this->addColumn('{{%page}}', 'middle_name', $this->string());
        $this->update('{{%page}}',
            [
                'last_name' => new \yii\db\Expression('SUBSTRING_INDEX(title, " ", 1)'),
                'first_name' => new \yii\db\Expression('SUBSTRING_INDEX(SUBSTRING_INDEX(title, " ", 2), " ", -1)'),
                'middle_name' => new \yii\db\Expression('SUBSTRING_INDEX(title, " ", -1)'),
            ]);
        $this->dropColumn('{{%page}}', 'title');
    }
}
