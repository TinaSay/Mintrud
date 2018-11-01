<?php

use app\modules\event\components\Populate;
use yii\db\Migration;

class m170624_102726_event_populate extends Migration
{
    public function safeUp()
    {
        $this->truncateTable('{{%event}}');
        $file = fopen(__DIR__ . '/data/atlanta_pages.csv', 'rb');
        while ($row = fgetcsv($file)) {
            $populate = new Populate($row, 'events', 6);
            if ($populate->isPage() && $populate->isFitsCategory()) {
                $this->insert(
                    '{{%event}}',
                    [
                        'id' => $populate->getId(),
                        'title' => $populate->getTitle(),
                        'text' => $populate->getText(),
                        'date' => $populate->getDate(),
                        'language' => $populate->getLanguage(),
                        'created_at' => $populate->getDate(),
                        'updated_at' => $populate->getDate(),
                    ]
                );
            };
        }
    }

    public function safeDown()
    {

    }
}
