<?php

use yii\db\Migration;

/**
 * Class m180221_112927_alter_news_table
 */
class m180221_112927_add_show_on_sovet_column_to_news_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn(
            '{{%news}}',
            'show_on_sovet',
            $this->smallInteger()->defaultValue(0)->notNull()
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('{{%news}}', 'show_on_sovet');
    }
}
