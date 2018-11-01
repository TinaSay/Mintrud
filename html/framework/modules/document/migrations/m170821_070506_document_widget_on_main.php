<?php

use yii\db\Migration;
use yii\db\Query;

class m170821_070506_document_widget_on_main extends Migration
{
    public function safeUp()
    {
        $this->addColumn('{{%document_widget_on_main}}', 'title', $this->string(512)->after('type_document_id'));

        $widgets = (new Query())->from('{{%document_widget_on_main}}')->all();

        foreach ($widgets as $widget) {
            $type = (new Query())
                ->from('{{%type_document}}')
                ->andWhere(['id' => $widget['type_document_id']])
                ->limit(1)
                ->one();

            $this->update(
                '{{%document_widget_on_main}}',
                [
                    'title' => $type['title']
                ],
                [
                    'id' => $widget['id']
                ]
            );
        }

        $this->alterColumn('{{%document_widget_on_main}}', 'title', $this->string(512)->notNull());
    }

    public function safeDown()
    {
        echo "m170821_070506_document_widget_on_main cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170821_070506_document_widget_on_main cannot be reverted.\n";

        return false;
    }
    */
}
