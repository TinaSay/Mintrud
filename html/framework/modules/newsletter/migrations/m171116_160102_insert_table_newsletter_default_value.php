<?php

use yii\db\Migration;
use app\modules\newsletter\models\Newsletter;

class m171116_160102_insert_table_newsletter_default_value extends Migration
{
    public function safeUp()
    {
        $this->update('{{%newsletter}}', ['isNews' => Newsletter::IS_NEWS_YES], ['isNews' => Newsletter::IS_NEWS_NO,
            'isEvent' => Newsletter::IS_EVENT_NO]);
    }

    public function safeDown()
    {
        echo "m171116_160102_insert_table_newsletter_default_value cannot be reverted.\n";

        return false;
    }
}
