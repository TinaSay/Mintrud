<?php

use app\modules\ministry\models\Ministry;
use yii\db\Expression;
use yii\db\Migration;

class m170819_054215_ministry_root_folders extends Migration
{
    public function safeUp()
    {

        // $this->truncateTable(Ministry::tableName());

        $rootFolder = [
            [
                'url' => 'ministry',
                'title' => 'Министерство',
            ],
            [
                'url' => 'zarplata',
                'title' => 'Программа поэтапного совершенствования системы оплаты труда на 2012-2018 годы',
            ],
            [
                'url' => 'reception',
                'title' => 'Обращения граждан',
            ],
            [
                'url' => 'nsok',
                'title' => 'Независимая система оценки качества',
            ],
            [
                'url' => 'sovet',
                'title' => 'Общественный совет',
            ],
        ];
        $pos = 0;
        foreach ($rootFolder as $folder) {
            $pos++;
            $this->insert(Ministry::tableName(), [
                'url' => $folder['url'],
                'title' => $folder['title'],
                'type' => Ministry::TYPE_FOLDER,
                'hidden' => Ministry::HIDDEN_NO,
                'depth' => 0,
                'position' => $pos,
            ]);

            if ($folder['url'] == 'ministry') {
                $ministryId = $this->getDb()->lastInsertID;

                $this->update(Ministry::tableName(), [
                    'url' => new Expression('CONCAT(\'ministry\/\', [[url]])'),
                    'depth' => 1,
                    'position' => new Expression('[[id]]'),
                    'parent_id' => $ministryId,
                ], [
                    'IS',
                    'parent_id',
                    null,
                ]);

                $this->update(Ministry::tableName(),
                    [
                        'parent_id' => null,
                        'url' => 'ministry',
                        'depth' => 0,
                    ],
                    [
                        'id' => $ministryId,
                    ]
                );

                $folders = Ministry::find()->where([
                    'parent_id' => $ministryId,
                ])->asArray()
                    ->all();
                foreach ($folders as $subFolder) {
                    $this->update(Ministry::tableName(), [
                        'url' => new Expression('CONCAT(\'ministry\/\', [[url]])'),
                        'depth' => $subFolder['depth'] + 1,
                        'position' => new Expression('[[id]]'),
                    ], [
                        'parent_id' => $subFolder['id'],
                        'type' => Ministry::TYPE_ARTICLE,
                    ]);
                }
            }
        }
    }

    public function safeDown()
    {
        $this->truncateTable(Ministry::tableName());

        echo "m170819_054215_ministry_root_folders - reverted.\n";
    }

}
