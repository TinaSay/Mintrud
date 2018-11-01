<?php

use yii\db\Migration;

class m170904_041614_menu_title extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%ministry}}', 'menu_title',
            $this->string(255)->notNull()->defaultValue('')->after('title'));
        $this->addColumn('{{%ministry}}', 'layout',
            $this->string(255)->notNull()->defaultValue('//common')->after('url'));
        $this->addColumn('{{%ministry}}', 'show_menu',
            $this->smallInteger(1)->notNull()->defaultValue(1)->after('depth'));
        $this->addColumn('{{%ministry}}', 'deep_menu',
            $this->smallInteger(1)->notNull()->defaultValue(0)->after('show_menu'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%ministry}}', 'show_menu');
        $this->dropColumn('{{%ministry}}', 'deep_menu');
        $this->dropColumn('{{%ministry}}', 'menu_title');
        $this->dropColumn('{{%ministry}}', 'layout');
    }

}
