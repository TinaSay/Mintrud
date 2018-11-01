<?php

use yii\db\Migration;
use yii\db\Expression;

/**
 * Class m180110_101328_fixture_for_banner
 */
class m180110_101328_fixture_for_banner extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('{{%banner_category}}', 'title', $this->string(255)->notNull()->defaultValue(''));
        $this->alterColumn('{{%banner}}', 'title', $this->string(255)->notNull()->defaultValue(''));

        $rows = [
            'Ключевые государственные информационные ресурсы' => [
                ['title' => 'Сайт Президента Российской Федерации', 'url' => 'kremlin.ru'],
                ['title' => 'Правительство Российской Федерации', 'url' => 'government.ru'],
                ['title' => 'Справочно-информационный портал «Государственные услуги»', 'url' => 'https://www.gosuslugi.ru'],
                ['title' => 'Сайт Государственной Думы', 'url' => 'http://www.duma.gov.ru'],
                ['title' => 'Сайт Совета Федерации', 'url' => 'http://www.council.gov.ru'],
                ['title' => 'Сайт Конституционного суда РФ', 'url' => 'http://www.ksrf.ru/ru'],
                ['title' => 'Сайт РОИ', 'url' => 'https://www.roi.ru'],
                ['title' => 'Федеральный портал проектов НПА', 'url' => 'http://regulation.gov.ru'],
                ['title' => 'Портал открытых данных Российской Федерации', 'url' => 'http://data.gov.ru'],
                ['title' => 'Государственная система правовой информации', 'url' => 'http://pravo.gov.ru'],
            ],
            'Информационные порталы и информация Минтруда России' => [
                ['title' => 'Результаты независимой системы оценки качества работы организаций, оказывающих социальные услуги', 'url' => 'http://bus.gov.ru/pub/independentRating/list'],
                ['title' => 'Горячая линия по вопросам повышения оплаты труда работников государственных и муниципальных учреждений', 'url' => '/ministry/programms/27'],
                ['title' => 'Горячая линия по вопросам инвалидности', 'url' => 'http://www.hotline-inv.info'],
                ['title' => 'Исполнение указов Президента Российской Федерации от 7 мая 2012 года', 'url' => '/ministry/programms/9'],
                ['title' => 'Государственный доклад о положении детей и семей, имеющих детей, в Российской Федерации', 'url' => '/docs/mintrud/protection/256'],
                ['title' => 'ФЗ «Об основах социального обслуживания граждан в РФ»', 'url' => '/docs/mintrud/protection/133'],
                ['title' => 'Интернет-портал «Жить вместе»', 'url' => 'http://zhit-vmeste.ru/'],
                ['title' => 'Государственная гражданская служба', 'url' => '/ministry/programms/gossluzhba'],
                ['title' => 'Пилотные проекты по внедрению новых принципов кадровой политики на государственной гражданской службе', 'url' => '/ministry/programms/gossluzhba/4'],
                ['title' => 'Статистическая информация о ситуации на регистрируемом рынке труда', 'url' => 'https://www.rostrud.ru/rostrud/deyatelnost/?CAT_ID=6293'],
                ['title' => 'Стратегия действий в интересах граждан пожилого возраста', 'url' => 'http://www.rosmintrud.ru/login?came_from=http%3A//www.rosmintrud.ru/discussions/elderly/index_html'],
                ['title' => 'Фонд поддержки детей, находящихся в трудной жизненной ситуации', 'url' => 'http://fond-detyam.ru/'],
                ['title' => 'Концепция государственной семейной политики в России', 'url' => '/ministry/programms/16'],
                ['title' => 'Независимая оценка квалификации', 'url' => '/ministry/programms/22'],
            ],
            'Cервисы и автоматизированные информационные системы' => [
                ['title' => 'Реформа контрольной и надзорной деятельности', 'url' => 'http://контроль-надзор.рф'],
                ['title' => 'Справочник профессий', 'url' => 'http://spravochnik.rosmintrud.ru'],
                ['title' => 'Профессиональные стандарты', 'url' => 'http://profstandart.rosmintrud.ru'],
                ['title' => 'Общероссийская база вакансий «Работа в России»', 'url' => 'https://trudvsem.ru'],
                ['title' => 'Программа поэтапного совершенствования системы оплаты труда на 2012-2018 годы', 'url' => '/zarplata'],
                ['title' => 'Демографический атлас', 'url' => '/2025/atlas'],
                ['title' => 'Пенсионный калькулятор', 'url' => 'http://www.pfrf.ru/eservices/calc/'],
                ['title' => 'Единая общероссийская справочно-информационная система по охране труда', 'url' => 'http://akot.rosmintrud.ru'],
                ['title' => 'Данные Минтруда России в Единой межведомственной информационно-статистической системе (ЕМИСС)', 'url' => 'http://fedstat.ru/indicators/org.do?id=66'],
                ['title' => 'Гражданам Украины и лицам без гражданства, покинувшим территорию страны в экстренном и массовом порядке', 'url' => 'https://www.gosuslugi.ru/migrant'],
                ['title' => 'Участие в опросе качества услуг в социальном обслуживании', 'url' => '/nsok/survey_citizens'],
                ['title' => 'АИК «Миграционные квоты»', 'url' => 'http://www.migrakvota.gov.ru/'],
                ['title' => 'Портал оперативного взаимодействия участников СМЭВ', 'url' => 'http://forum.minsvyaz.ru/'],
            ],
                'Подведомственные организации' => [
                    ['title' => 'Всероссийский научно-исследовательский институт труда Минтруда России', 'url' => 'http://www.vcot.info/'],
            ],
            'Неправительственные организации' => [
                ['title' => 'Общественная палата Российской Федерации', 'url' => 'http://www.oprf.ru/'],
                ['title' => 'Российский союз промышленников и предпринимателей', 'url' => 'http://www.rspp.ru/'],
                ['title' => 'Федерация независимых профсоюзов России', 'url' => 'http://www.fnpr.ru/'],
                ['title' => 'Базовый центр подготовки, переподготовки и повышения квалификации рабочих кадров', 'url' => 'http://worldskills.ru/bazovyy-centr/'],
                ['title' => 'Российский союз промышленников и предпринимателей', 'url' => 'http://www.rspp.ru/'],
                ['title' => 'Совет при Правительстве Российской Федерации по вопросам попечительства в социальной сфере', 'url' => 'http://www.popechitely.ru/'],
                ['title' => 'Всероссийское общество инвалидов', 'url' => 'http://www.voi.ru/'],
                ['title' => 'Всероссийское общество глухих', 'url' => 'http://www.voginfo.ru/'],
                ['title' => 'Российская общественная организация «Перспектива»', 'url' => 'https://perspektiva-inva.ru/'],
                ['title' => 'Межрегиональная общественная организация инвалидов «Пилигрим»', 'url' => 'http://www.pilig.ru/'],
                ['title' => '«Старость в радость» – сбор новогодних подарков в дома престарелых', 'url' => 'https://starikam.org/home/newyear2012/'],
                ['title' => 'Информационная система Совета Европы по вопросам социального обеспечения', 'url' => 'http://www.coe.int/en/web/turin-european-social-charter/european-code-of-social-security'],
            ],
            'Мероприятия и конкурсы' => [
                ['title' => 'Всероссийская неделя охраны труда', 'url' => 'http://www.vssot.aetalon.ru/'],
                ['title' => 'Всероссийский Форум-выставка «ГОСЗАКАЗ – за честные закупки»', 'url' => 'https://forum-goszakaz.ru/'],
                ['title' => 'ХХII Петербургский международный экономический форум (24-26 мая 2018 г.)', 'url' => 'http://www.forumspb.com/ru/'],
                ['title' => 'III Восточный экономический форум (6-7 сентября 2018 г.)', 'url' => 'https://forumvostok.ru/'],
                ['title' => 'Год экологии в России', 'url' => 'http://ecoyear.ru/'],
                ['title' => 'Всероссийский конкурс «Российская организация высокой социальной эффективности»', 'url' => '/events/550'],
                ['title' => 'Конкурс «Лучшие кадровые практики на государственной гражданской и муниципальной службе»', 'url' => '/ministry/programms/gossluzhba/12'],
                ['title' => 'Выдвижение Екатеринбурга на право проведения ЭКСПО-2025', 'url' => 'http://government.ru/docs/29139/'],
                ['title' => 'Всероссийский конкурс на лучшую организацию работ в области условий и охраны труда «Успех и безопасность»', 'url' => '/docs/mintrud/orders/271'],
            ],
        ];

        $data = [];
        foreach ($rows as $key => $row) {
            $category = new \app\modules\banner\models\BannerCategory();

            $category->setAttributes([
                'title' => $key,
                'created_by' => 1,
                'created_at' => new Expression('NOW()'),
                'updated_at' => new Expression('NOW()'),
            ], false);

            $category->save(false);

            foreach ($row as $item) {
                $data[] = [
                    'title' => $item['title'],
                    'url' => $item['url'],
                    'category_id' => $category->id,
                    'created_at' => new Expression('NOW()'),
                    'updated_at' => new Expression('NOW()'),
                ];
            }
        }

        $this->batchInsert('{{%banner}}', ['title', 'url', 'category_id', 'created_at', 'updated_at'], $data);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->alterColumn('{{%banner_category}}', 'title', $this->string(64)->notNull()->defaultValue(''));
        $this->alterColumn('{{%banner}}', 'title', $this->string(64)->notNull()->defaultValue(''));

        $this->truncateTable('{{%banner_category}}');
        $this->truncateTable('{{%banner}}');
    }
}
