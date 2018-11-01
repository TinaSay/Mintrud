<?php

use yii\db\Migration;
use yii\db\Query;

class m170807_055137_directory_add_data extends Migration
{
    public function safeUp()
    {
        $data = [
            'Трудовые отношения' => [
                [
                    'title' => 'Социальное партнерство и трудовые отношения',
                    'url' => 'relationship',
                ],
                [
                    'title' => 'Охрана труда',
                    'url' => 'safety',
                ],
                [
                    'title' => 'Государственная гражданская служба',
                    'url' => 'public-service',
                ],
                [
                    'title' => 'Оплата труда',
                    'url' => 'salary',
                ],
                [
                    'title' => 'Профессиональные стандарты',
                    'url' => 'standard',
                ],
                [
                    'title' => 'Защита прав трудящихся',
                    'url' => 'protection',
                ],
                [
                    'title' => 'Социальное партнерство',
                    'url' => 'partnership'
                ],
                [
                    'title' => 'Международное сотрудничество в сфере трудовых отношений',
                    'url' => 'cooperation'
                ],
                [
                    'title' => 'Профессиональное образование',
                    'url' => 'education',
                ],
                [
                    'title' => 'Оплата труда бюджетников',
                    'url' => 'employee',
                ],
                [
                    'title' => 'Альтернативная гражданская служба',
                    'url' => 'alternative-service',
                ],
                [
                    'title' => 'Независимая оценка квалификации',
                    'url' => 'nok',
                ],
            ],
            'Занятость населения' => [
                [
                    'title' => 'Рынок труда',
                    'url' => 'Рынок труда',
                ],
                [
                    'title' => 'Трудовая миграция',
                    'url' => 'migration',
                ],
                [
                    'title' => 'Занятость населения в бюджетной сфере',
                    'url' => 'budjet',
                ],
                [
                    'title' => 'Трудоустройство людей с ограниченными возможностями',
                    'url' => 'resettlement',
                ],
                [
                    'title' => 'Международное сотрудничество в сфере занятости',
                    'url' => 'cooperation',
                ],
            ],
            'Социальная защита' => [
                [
                    'title' => 'Социальная защита инвалидов',
                    'url' => 'invalid-defence',
                ],
                [
                    'title' => 'Социальная политика',
                    'url' => 'social',
                ],
                [
                    'title' => 'Социальная политика в отношении семьи женщин и детей',
                    'url' => 'family',
                ],
                [
                    'title' => 'Социальное обслуживание граждан',
                    'url' => 'service',
                ],
                [
                    'title' => 'Социальное страхование',
                    'url' => 'insurance',
                ],
                [
                    'title' => 'Демографическая политика',
                    'url' => 'demography',
                ],
                [
                    'title' => 'Социальная защита пожилых',
                    'url' => 'vetaran-defence',
                ],
                [
                    'title' => 'Социальная защита граждан пострадавших в результате чрезвычайных ситуаций',
                    'url' => 'force-majeur',
                ],
                [
                    'title' => 'Независимая система оценки качества',
                    'url' => 'nsok',
                ],
                [
                    'title' => 'Уровень жизни и доходов населения',
                    'url' => 'living-standard',
                ],
                [
                    'title' => 'Международное сотрудничество в социальной сфере',
                    'url' => 'cooperation',
                ],
                [
                    'title' => 'Фонд поддержки детей находящихся в трудной жизненной ситуации',
                    'url' => 'fund-children',
                ],
            ],
            'Пенсионное обеспечение' => [
                [
                    'title' => 'Совершенствование пенсионной системы',
                    'url' => 'razvitie',
                ],
                [
                    'title' => 'Назначение и выплата пенсий',
                    'url' => 'pension',
                ],
                [
                    'title' => 'Пенсионное страхование',
                    'url' => 'insurance',
                ],
                [
                    'title' => 'Увеличение пенсий',
                    'url' => 'increase',
                ],
                [
                    'title' => 'Формирование пенсионных накоплений',
                    'url' => 'financing',
                ],
                [
                    'title' => 'Индексация пенсий',
                    'url' => 'indexing',
                ],
                [
                    'title' => 'Негосударственное пенсионное обеспечение',
                    'url' => 'chastnoe',
                ],
                [
                    'title' => 'Международное сотрудничество в сфере пенсионного обеспечения',
                    'url' => 'cooperation',
                ],
            ],
        ];

        foreach ($data as $name => $datum) {
            $directory = (new Query())
                ->from('{{%directory}}')
                ->andWhere(['title' => $name])
                ->andWhere(['type' => \app\modules\directory\rules\type\TypeInterface::TYPE_DESCRIPTION_DIRECTORY])
                ->limit(1)
                ->one();
            foreach ($datum as $item) {
                $this->insert(
                    '{{%directory}}',
                    [
                        'parent_id' => $directory['id'],
                        'type' => \app\modules\directory\rules\type\TypeInterface::TYPE_DIRECTION,
                        'title' => $item['title'],
                        'fragment' => $item['url'],
                        'url' => $directory['url'] . '/' . $item['url'],
                        'depth' => 1,
                        'language' => 'ru-RU',
                        'created_at' => (new DateTime())->format('Y-m-d'),
                        'updated_at' => (new DateTime())->format('Y-m-d'),
                    ]
                );
            }
        }
    }

    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m170807_055137_directory_add_data cannot be reverted.\n";

        return false;
    }
    */
}
