<?php

use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\auth\models\Auth;
use yii\db\Expression;
use yii\db\Migration;

class m170720_103114_directory_rates extends Migration
{
    public function safeUp()
    {

        $list = [
            'ЧИСЛО РОДИВШИХСЯ' => [
                'Число родившихся, чел.',
                'Число родившихся, на 1000 чел.',
            ],
            'ЧИСЛО УМЕРШИХ' => [
                'Число умерших, чел.',
                'Число умерших, на 1000 чел.',
            ],
            'ЕСТЕСТВЕННЫЙ ПРИРОСТ/УБЫЛЬ' => [
                'Естественный прирост/убыль, чел.',
                'Естественный прирост/убыль, на 1000 чел.',
            ],
            'МЛАДЕНЧЕСКАЯ СМЕРТНОСТЬ' => [
                'Младенческая смертность, чел.',
                'Младенческая смертность, на 1000 чел.',
            ],
            'ОЖИДАЕМАЯ ПРОДОЛЖИТЕЛЬНОСТЬ ЖИЗНИ' => [
                'Ожидаемая продолжительность жизни, общая',
                'Ожидаемая продолжительность жизни, мужчин',
                'Ожидаемая продолжительность жизни, женщин',
            ],
            'СУММАРНЫЙ КОЭФФИЦИЕНТ РОЖДАЕМОСТИ' => [
                'Суммарный коэффициент рождаемости',
            ],
        ];
        $pos = 0;
        $user = Auth::findOne(['login' => 'webmaster']);

        foreach ($list as $group => $types) {
            $pos++;
            $this->getDb()->createCommand()->insert(AtlasDirectoryRate::tableName(), [
                'title' => $group,
                'position' => $pos,
                'hidden' => AtlasDirectoryRate::HIDDEN_NO,
                'language' => Yii::$app->language,
                'type' => AtlasDirectoryRate::getType(),
                'created_by' => $user->getId(),
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ])->execute();
            $parent_id = $this->getDb()->lastInsertID;
            foreach ($types as $type) {
                $pos++;
                $this->getDb()->createCommand()->insert(AtlasDirectoryRate::tableName(), [
                    'title' => $type,
                    'parent_id' => $parent_id,
                    'depth' => AtlasDirectoryRate::DEPTH_ROOT + 1,
                    'position' => $pos,
                    'hidden' => AtlasDirectoryRate::HIDDEN_NO,
                    'language' => Yii::$app->language,
                    'type' => AtlasDirectoryRate::getType(),
                    'created_by' => $user->getId(),
                    'created_at' => new Expression('NOW()'),
                    'updated_at' => new Expression('NOW()'),
                ])->execute();
            }
        }
    }

    public function safeDown()
    {

        AtlasDirectoryRate::deleteAll(['type' => AtlasDirectoryRate::getType()]);

        echo "m170720_103114_directory_rates - reverted.\n";

        return true;
    }

}
