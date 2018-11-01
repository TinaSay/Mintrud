<?php

use app\modules\page\models\Page;
use yii\db\Migration;

class m171030_104943_hide_pages extends Migration
{
    public function safeUp()
    {
        Page::updateAll(['hidden' => Page::HIDDEN_YES]);
    }

    public function safeDown()
    {
        Page::updateAll(['hidden' => Page::HIDDEN_NO]);
    }

}
