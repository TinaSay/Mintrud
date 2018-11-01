<?php

use app\modules\atlas\models\AtlasDirectoryRate;
use app\modules\auth\models\Auth;
use yii\db\Expression;
use yii\db\Migration;

class m170726_080404_directory_type_code extends Migration
{
    public function safeUp()
    {

        $this->addColumn('{{%atlas_directory}}', 'code',
            $this->string(64)->notNull()->defaultValue('')->after('title')
        );
        $this->addColumn('{{%atlas_directory}}', 'stat_type',
            $this->smallInteger(1)->notNull()->defaultValue(0)->after('code')
        );
        $this->addColumn('{{%atlas_directory}}', 'value',
            $this->smallInteger(1)->notNull()->defaultValue(0)->after('code')
        );

        $list = [
            [
                'code' => 'born',
                'title' => 'Число родившихся',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR_DIFF,
                'children' => [
                    [
                        'title' => 'Число родившихся, чел.',
                        'value' => AtlasDirectoryRate::VALUE_NO,
                        'code' => 'abs',
                    ],
                    [
                        'title' => 'Число родившихся, на 1000 чел.',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],
                ],
            ],
            [
                'code' => 'died',
                'title' => 'Число умерших',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR_DIFF,
                'children' => [
                    [
                        'title' => 'Число умерших, чел.',
                        'value' => AtlasDirectoryRate::VALUE_NO,
                        'code' => 'abs',
                    ],
                    [
                        'title' => 'Число умерших, на 1000 чел.',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],
                ],
            ],
            [
                'code' => 'growth',
                'title' => 'Естественный прирост/убыль',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR,
                'children' => [
                    [
                        'title' => 'Естественный прирост/убыль, чел.',
                        'value' => AtlasDirectoryRate::VALUE_NO,
                        'code' => 'abs',
                    ],
                    [
                        'title' => 'Естественный прирост/убыль, на 1000 чел.',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],
                ],
            ],
            [
                'code' => 'infant',
                'title' => 'Младенческая смертность',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR,
                'children' => [
                    [
                        'title' => 'Младенческая смертность, чел.',
                        'value' => AtlasDirectoryRate::VALUE_NO,
                        'code' => 'abs',
                    ],
                    [
                        'title' => 'Младенческая смертность, на 1000 чел.',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],
                ],
            ],
            [
                'code' => 'life',
                'title' => 'Ожидаемая продолжительность жизни',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR,
                'children' => [
                    [
                        'title' => 'Ожидаемая продолжительность жизни, общая',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],
                    [
                        'title' => 'Ожидаемая продолжительность жизни, мужчин',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                        'code' => 'life_w',
                    ],
                    [
                        'title' => 'Ожидаемая продолжительность жизни, женщин',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                        'code' => 'life_m',
                    ],
                ],
            ],
            [
                'code' => 'babies',
                'title' => 'Суммарный коэффициент рождаемости',
                'stat' => AtlasDirectoryRate::STAT_TYPE_YEAR,
                'children' => [
                    [
                        'title' => 'Суммарный коэффициент рождаемости',
                        'value' => AtlasDirectoryRate::VALUE_YES,
                    ],

                ],
            ],
            [
                'code' => 'total',
                'title' => 'Всего населенеия',
                'stat' => AtlasDirectoryRate::STAT_TYPE_NONE,
                'hidden' => AtlasDirectoryRate::HIDDEN_YES,
            ],
        ];
        $pos = 0;
        $user = Auth::findOne(['login' => 'webmaster']);

        foreach ($list as $item) {
            $pos++;
            $exists = AtlasDirectoryRate::find()->select(['id'])->where([
                'like',
                'title',
                $item['title'],
                false,
            ])->andWhere([
                'IS',
                'parent_id',
                null,
            ])->limit(1)->column();

            if ($exists) {
                $parent_id = current($exists);
                $this->update(
                    AtlasDirectoryRate::tableName(),
                    [
                        'code' => $item['code'],
                        'title' => $item['title'],
                        'stat_type' => $item['stat'],
                        'hidden' => isset($item['hidden']) ? $item['hidden'] : AtlasDirectoryRate::HIDDEN_NO,
                    ],
                    [
                        'id' => $parent_id,
                    ]
                );
            } else {
                $this->insert(AtlasDirectoryRate::tableName(), [
                    'title' => $item['title'],
                    'code' => $item['code'],
                    'stat_type' => $item['stat'],
                    'position' => $pos,
                    'hidden' => AtlasDirectoryRate::HIDDEN_NO,
                    'language' => Yii::$app->language,
                    'type' => AtlasDirectoryRate::getType(),
                    'created_by' => $user->getId(),
                    'created_at' => new Expression('NOW()'),
                    'updated_at' => new Expression('NOW()'),
                ]);
                $parent_id = $this->getDb()->lastInsertID;
            }
            if (isset($item['children'])) {
                $pos++;
                foreach ($item['children'] as $child) {
                    $exists = AtlasDirectoryRate::find()->select(['id'])->where([
                        'like',
                        'title',
                        $child['title'],
                        false,
                    ])->andWhere([
                        'parent_id' => $parent_id,
                    ])->limit(1)->column();
                    if ($exists) {
                        $this->update(
                            AtlasDirectoryRate::tableName(),
                            [
                                'code' => (isset($child['code']) ? $child['code'] : ''),
                                'value' => $child['value'],
                                'title' => $child['title'],
                            ],
                            [
                                'id' => current($exists),
                            ]
                        );
                    } else {
                        $this->insert(AtlasDirectoryRate::tableName(), [
                            'parent_id' => $parent_id,
                            'title' => $child['title'],
                            'code' => (isset($child['code']) ? $child['code'] : ''),
                            'value' => $child['value'],
                            'position' => $pos,
                            'hidden' => AtlasDirectoryRate::HIDDEN_NO,
                            'language' => Yii::$app->language,
                            'type' => AtlasDirectoryRate::getType(),
                            'created_by' => $user->getId(),
                            'created_at' => new Expression('NOW()'),
                            'updated_at' => new Expression('NOW()'),
                        ]);
                    }
                }
            }
        }
    }

    public function safeDown()
    {
        $this->dropColumn('{{%atlas_directory}}', 'value');
        $this->dropColumn('{{%atlas_directory}}', 'code');
        $this->dropColumn('{{%atlas_directory}}', 'stat_type');

        echo "m170726_080404_directory_type_code - reverted.\n";
    }

}
