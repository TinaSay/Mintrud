<?php

use app\modules\directory\rules\type\TypeInterface;
use yii\db\Migration;

class m170720_092234_directory_data extends Migration
{
    public function safeUp()
    {
        $data = [
            [
                'type' => TypeInterface::TYPE_DESCRIPTION_DIRECTORY,
                'fragment' => 'labour',
                'url' => 'labour',
                'title' => 'Трудовые отношения',
                'language' => 'ru-RU',
            ],
            [
                'type' => TypeInterface::TYPE_DESCRIPTION_DIRECTORY,
                'fragment' => 'employment',
                'url' => 'employment',
                'title' => 'Занятость населения',
                'language' => 'ru-RU',
            ],
            [
                'type' => TypeInterface::TYPE_DESCRIPTION_DIRECTORY,
                'fragment' => 'social',
                'url' => 'social',
                'title' => 'Социальная защита',
                'language' => 'ru-RU',
            ],
            [
                'type' => TypeInterface::TYPE_DESCRIPTION_DIRECTORY,
                'fragment' => 'pensions',
                'url' => 'pensions',
                'title' => 'Пенсионное обеспечение',
                'language' => 'ru-RU',
            ],
        ];

        foreach ($data as $datum) {
            $this->insert(
                '{{%directory}}',
                $datum
            );
        }
    }

    public function safeDown()
    {

    }
}
