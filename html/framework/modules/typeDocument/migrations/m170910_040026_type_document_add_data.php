<?php

use yii\db\Migration;

class m170910_040026_type_document_add_data extends Migration
{
    public function safeUp()
    {
        $this->delete(
            '{{%type_document}}',
            ['IN', 'title', ['Комментарий', 'Определение',]]
        );

        $this->insert(
            '{{%type_document}}',
            [
                'title' => 'Комментарий',
                'created_at' => (new DateTime())->format('Y-m-d'),
                'updated_at' => (new DateTime())->format('Y-m-d'),
            ]
        );

        $this->insert(
            '{{%type_document}}',
            [
                'title' => 'Определение',
                'created_at' => (new DateTime())->format('Y-m-d'),
                'updated_at' => (new DateTime())->format('Y-m-d'),
            ]
        );
    }

    public function safeDown()
    {
        $this->delete(
            '{{%type_document}}',
            ['IN', 'title', ['Комментарий', 'Определение',]]
        );
    }
}
