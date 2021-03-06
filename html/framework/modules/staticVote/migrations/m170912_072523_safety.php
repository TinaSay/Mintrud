<?php

use app\modules\staticVote\models\StaticVoteAnswers;
use app\modules\staticVote\models\StaticVoteQuestion;
use app\modules\staticVote\models\StaticVoteQuestionnaire;
use yii\db\Migration;
use yii\helpers\Json;

class m170912_072523_safety extends Migration
{
    public function safeUp()
    {
        $questionnaire = StaticVoteQuestionnaire::findOne(['alias' => 'safety']);
        if ($questionnaire) {
            StaticVoteAnswers::deleteAll(['questionnaire_id' => $questionnaire->id]);
            StaticVoteQuestion::deleteAll(['questionnaire_id' => $questionnaire->id]);
            StaticVoteQuestionnaire::deleteAll(['id' => $questionnaire->id]);
        }

        $list = [
            [
                'title' => 'Опрос с целью содействия государственным реформам в области охраны труда, связанным с повышением безопасности на рабочих местах',
                'text' => '<p>Минтруд России проводит опрос с целью содействия государственным реформам в области охраны труда, связанным с повышением безопасности на рабочих местах.<br>Ваше мнение очень важно для нас!</p>',
                'alias' => 'safety',
                'questions' => [
                    [
                        'title' => 'Как Вы относитесь к вопросам охраны и безопасности труда на Вашем рабочем месте?',
                        'answers' => [
                            1 => 'Я знаю требования охраны и безопасности труда и понимаю важность их соблюдения',
                            'Я в общих чертах знаком с требованиями охраны труда',
                            'Моя работа не связана с опасностями и вредными факторами, ко мне это не относится',
                            'Охрана труда – это, скорее, формальность',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                    ],
                    [
                        'title' => 'Кто, по Вашему мнению, больше всего должен быть заинтересован в вопросах безопасности на работе?',
                        'answers' => [
                            1 => 'Это обязанность работодателя, он должен показывать пример и заботиться о сотрудниках',
                            'Это обязанность службы охраны труда, они за это получают зарплату',
                            'Работники сами должны не формально, а внимательно относиться к вопросам охраны труда, так как речь идет об их собственной жизни и здоровье',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                    ],
                    [
                        'title' => 'Что является самым важным с точки зрения обеспечения безопасности работников?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_NONE,
                        'hint' => 'отметьте, что из перечисленного есть в Вашей организации, а затем укажите <i>не более 4 наиболее важных</i>, с Вашей точки зрения, мероприятий, даже если они не применяются в Вашей организации',
                    ],
                    [
                        'title' => 'Есть у нас',
                        'input' => StaticVoteQuestion::INPUT_TYPE_CHECKBOX,
                        'answers' => [
                            1 => 'Руководители показывают личный пример в соблюдении требований безопасности',
                            'Работники знают о конкретных опасностях и рисках для здоровья на рабочих местах и о мерах по их защите от этих опасностей',
                            'Существует четкое распределение ответственности за безопасность выполнения работы',
                            'Есть понятные документы, правила, инструкции и требования в области охраны и безопасности труда',
                            'Работники получают наглядную информацию по вопросам охраны труда, в том числе имеются стенды, плакаты, предупреждающие надписи и знаки',
                            'Используется современное и безопасное оборудование и технологии',
                            'Проводятся качественные инструктажи по охране труда и обучение безопасным приемам и методам работы ',
                            'Работники получают регулярную информацию о состоянии охраны труда ',
                            'Руководители работ дают необходимые разъяснения по вопросам безопасности',
                            'Знания и навыки каждого работника позволяют безопасно работать на своем оборудовании',
                            'Существует возможность для работников обсуждать вопросы своей безопасности с руководителем и быть услышанным',
                            'Работники всегда соблюдают требования безопасности, даже если есть более быстрый, но менее безопасный способ выполнения работы',
                            'Существуют поощрения за соблюдение требований охраны труда и внедрение предложений по повышению уровня безопасности',
                        ],
                        'child' => 'Что является самым важным с точки зрения обеспечения безопасности работников?',
                    ],
                    [
                        'title' => 'Считаю важным',
                        'input' => StaticVoteQuestion::INPUT_TYPE_CHECKBOX,
                        'answers' => [
                            1 => 'Руководители показывают личный пример в соблюдении требований безопасности',
                            'Работники знают о конкретных опасностях и рисках для здоровья на рабочих местах и о мерах по их защите от этих опасностей',
                            'Существует четкое распределение ответственности за безопасность выполнения работы',
                            'Есть понятные документы, правила, инструкции и требования в области охраны и безопасности труда',
                            'Работники получают наглядную информацию по вопросам охраны труда, в том числе имеются стенды, плакаты, предупреждающие надписи и знаки',
                            'Используется современное и безопасное оборудование и технологии',
                            'Проводятся качественные инструктажи по охране труда и обучение безопасным приемам и методам работы ',
                            'Работники получают регулярную информацию о состоянии охраны труда ',
                            'Руководители работ дают необходимые разъяснения по вопросам безопасности',
                            'Знания и навыки каждого работника позволяют безопасно работать на своем оборудовании',
                            'Существует возможность для работников обсуждать вопросы своей безопасности с руководителем и быть услышанным',
                            'Работники всегда соблюдают требования безопасности, даже если есть более быстрый, но менее безопасный способ выполнения работы',
                            'Существуют поощрения за соблюдение требований охраны труда и внедрение предложений по повышению уровня безопасности',
                        ],
                        'max' => 4,
                        'hint' => 'выберите не более 4 вариантов ответа',
                        'child' => 'Что является самым важным с точки зрения обеспечения безопасности работников?',
                    ],
                    [
                        'title' => 'Что, по Вашему мнению, может повысить безопасность работы в Вашей организации?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_CHECKBOX,
                        'answers' => [
                            1 => 'Возможность для работников открыто высказывать свое мнение, давать предложения и активно участвовать в разработке мероприятий, которые помогут сделать работу более безопасной',
                            'Использование более современного и безопасного оборудования и технологий',
                            'Более четкие и понятные инструкции и правила выполнения работ',
                            'Более правильная организация работы (меньше авралов и сверхурочной работы)',
                            'Больше наглядной информации по вопросам охраны труда (плакатов, листовок, знаков)',
                            'Более качественные и практические инструктажи и обучение по охране и безопасности труда',
                            'Более серьезное отношение к вопросам безопасности со стороны самих руководителей ',
                            'Наличие системы поощрений за соблюдение требований охраны труда и наказаний за их нарушение',
                            'Более внимательное отношение самих работников к своей безопасности и здоровью',
                            10 => 'Другое',
                        ],
                        'max' => 4,
                        'hint' => 'выберите не более 4 вариантов ответа',
                    ],
                    [
                        'title' => 'укажите, пожалуйста',
                        'input' => StaticVoteQuestion::INPUT_TYPE_TEXT,
                        'hint' => '(напишите)',
                        'child' => 'Что, по Вашему мнению, может повысить безопасность работы в Вашей организации?_10',
                    ],
                    [
                        'title' => 'Что, по Вашему мнению, побуждает работника соблюдать требования безопасности?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_CHECKBOX,
                        'answers' => [
                            1 => 'Замечание, сделанное руководителем или специалистом по охране труда',
                            'Замечание, сделанное коллегой',
                            'Тревога за свою жизнь, ответственность перед семьей и близкими людьми',
                            'Боязнь наказания со стороны руководства (штраф)',
                            'Поощрение за соблюдение требований безопасности',
                            'У нас в коллективе принято соблюдать требования безопасности и это для меня нормально',
                        ],
                        'max' => 3,
                        'hint' => 'выберите не более 3 вариантов ответа',
                    ],
                    [
                        'title' => 'Как Вы оцениваете состояние охраны и безопасности труда в Вашей организации?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'answers' => [
                            1 => 'Отлично (как работодатель, так и работники заботятся о сохранении жизни и здоровья на работе и демонстрируют это, мы делаем больше, чем прописано в правилах и инструкциях)',
                            'Хорошо (вопросы безопасности и охраны труда являются одним из принципов работы в нашей организации, осознанно соблюдаются все правила и требования)',
                            'Удовлетворительно (в нашей организации занимаются вопросами охраны и безопасности  труда, однако делается это формально) ',
                            'Плохо (охрана и безопасность труда никого не волнуют, требований и инструкций нет)',
                        ],
                    ],
                    [
                        'title' => 'Приходилось ли Вам за последний год нарушать требования охраны труда в связи с выполнением работы?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'answers' => [
                            1 => 'Нет, я всегда следую требованиям охраны труда',
                            'Да, но я очень редко пренебрегаю требованиями безопасности',
                            'Да, я часто пренебрегаю требованиями безопасности',
                            'Затрудняюсь ответить',
                        ],
                    ],
                    [
                        'title' => 'Что, на Ваш взгляд, служит причиной нарушения работниками требований безопасности?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_CHECKBOX,
                        'answers' => [
                            1 => 'Часто надеются на «авось»',
                            'Все нарушают требования, поэтому нет смысла их соблюдать отдельным работникам',
                            'Не всегда знают, как надо работать безопасно',
                            'Правила и требования к безопасному выполнению работы непонятны и не соответствуют практике',
                            'Большой объем работы, при котором невозможно соблюдать требования безопасности',
                            'Желание перевыполнить план и получить премию, даже если это влечет нарушение требований охраны труда ',
                            'Желание сэкономить время и сделать работу быстрее',
                        ],
                        'max' => 3,
                        'hint' => 'выберите не более 3 вариантов ответа',
                    ],
                    [
                        'title' => 'Теперь расскажите, пожалуйста, немного о себе',
                        'input' => StaticVoteQuestion::INPUT_TYPE_NONE,
                    ],
                    [
                        'title' => 'Как Вы оцениваете состояние охраны и безопасности труда в Вашей организации?',
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'answers' => [
                            1 => 'Отлично (как работодатель, так и работники заботятся о сохранении жизни и здоровья на работе и демонстрируют это, мы делаем больше, чем прописано в правилах и инструкциях)',
                            'Хорошо (вопросы безопасности и охраны труда являются одним из принципов работы в нашей организации, осознанно соблюдаются все правила и требования)',
                            'Удовлетворительно (в нашей организации занимаются вопросами охраны и безопасности  труда, однако делается это формально) ',
                            'Плохо (охрана и безопасность труда никого не волнуют, требований и инструкций нет)',
                        ],
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'Ваш пол',
                        'answers' => [
                            1 => 'мужской',
                            'женский',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'Ваш возраст',
                        'answers' => [
                            1 => 'до 20 лет',
                            '20-30 лет',
                            '30-40 лет',
                            '40-50 лет',
                            'старше 50 лет',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'Сколько Вы работаете по данной профессии/специальности?',
                        'answers' => [
                            1 => 'меньше 5 лет',
                            '5-10 лет',
                            '10-15 лет',
                            'больше 15 лет',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'Какую должность Вы занимаете или по какой профессии работаете?',
                        'answers' => [
                            1 => 'Руководитель высокого ранга (директор, заместитель директора)',
                            'Руководитель работ (начальник управления, отдела, мастер, начальник участка, цеха, бригады)',
                            'Отношусь к работникам основной профессии своего предприятия (машинист, нефтяник, строитель, металлург, учитель, врач и т.п.)',
                            'Специалист по охране труда, промышленной, пожарной или экологической безопасности',
                            'Офисный работник (ассистент, секретарь, бухгалтер, работник административного аппарата, сотрудник информационно-технической службы и т.п.)',
                            'Разнорабочий, выполняющий вспомогательные работы (грузчик, сортировщик и т.п.)',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'К какому виду экономической деятельности относится Ваше предприятие?',
                        'answers' => [
                            1 => 'Сельское хозяйство, охота, лесное хозяйство и предоставление услуг в этих областях',
                            'Рыболовство, рыбоводство и предоставление услуг в этих областях',
                            'Добыча полезных ископаемых (кроме нефти и газа)',
                            'Добыча нефти и газа',
                            'Обрабатывающее производство (обработка древесины, текстильное производство, пищевое производство, производство кокса и нефтепродуктов, ремонт и обслуживание автомобилей и техники)',
                            'Химическая промышленность',
                            'Машиностроение/приборостроение/автомобилестроение',
                            'Черная или цветная металлургия',
                            'Производство и распределение электроэнергии,  газа и воды',
                            'Строительство',
                            'Оптовая и розничная торговля; ремонт  автотранспортных средств, мотоциклов, бытовых изделий и  предметов личного пользования',
                            'Гостиницы и рестораны',
                            'Транспорт и связь',
                            'Финансовая деятельность',
                            'Операции с недвижимым имуществом, аренда и предоставление услуг',
                            'Государственное управление и обеспечение военной безопасности; обязательное социальное обеспечение',
                            'Образование',
                            'Здравоохранение и предоставление социальных услуг',
                            19 => 'Другое',
                        ],
                        'input' => StaticVoteQuestion::INPUT_TYPE_RADIO,
                        'child' => 'Теперь расскажите, пожалуйста, немного о себе',
                    ],
                    [
                        'title' => 'укажите, пожалуйста',
                        'input' => StaticVoteQuestion::INPUT_TYPE_TEXT,
                        'hint' => '(напишите)',
                        'child' => 'К какому виду экономической деятельности относится Ваше предприятие?_19',
                    ],
                ],
            ],
        ];
        foreach ($list as $questionnaire) {
            $this->insert(
                StaticVoteQuestionnaire::tableName(),
                [
                    'title' => $questionnaire['title'],
                    'text' => $questionnaire['text'],
                    'alias' => $questionnaire['alias'],
                ]
            );
            $questionnaire_id = Yii::$app->getDb()->getLastInsertID();
            $parent_ids = [];

            foreach ($questionnaire['questions'] as $question) {
                $show_on_answer_check = '';
                if (isset($question['child'])) {
                    if (preg_match("#\_#", $question['child'])) {
                        list($pq, $answer_id) = explode("_", $question['child']);
                        $answer_id = explode('|', $answer_id);
                        $parent_id = $parent_ids[$pq];
                        $show_on_answer_check = '';
                        foreach ($answer_id as $a_id) {
                            $show_on_answer_check .= ($show_on_answer_check ? '|' : '') .
                                $parent_id . "_" . $a_id;
                        }
                    } else {
                        $show_on_answer_check = (string)$parent_ids[$question['child']];
                    }

                    unset($question['child']);
                }
                $this->insert(
                    StaticVoteQuestion::tableName(),
                    [
                        'questionnaire_id' => $questionnaire_id,
                        'question' => trim($question['title']),
                        'hint' => isset($question['hint']) ? trim($question['hint']) : '',
                        'answers' => (array_key_exists('answers',
                            $question) ? Json::encode($question['answers']) : null),
                        'input_type' => $question['input'],
                        'min_answers' => isset($question['min']) ? (int)$question['min'] : 0,
                        'max_answers' => isset($question['max']) ? (int)$question['max'] : 0,
                        'show_on_answer_check' => $show_on_answer_check,
                    ]
                );
                $question_id = Yii::$app->getDb()->getLastInsertID();

                $parent_ids[$question['title']] = $question_id;

            }

        }

        return true;
    }

    public function safeDown()
    {
        $questionnaire = StaticVoteQuestionnaire::findOne(['alias' => 'safety']);
        if ($questionnaire) {
            StaticVoteAnswers::deleteAll(['questionnaire_id' => $questionnaire->id]);
            StaticVoteQuestion::deleteAll(['questionnaire_id' => $questionnaire->id]);
            StaticVoteQuestionnaire::deleteAll(['id' => $questionnaire->id]);
        }

        echo "m170912_072523_safety - reverted.\n";

        return true;
    }
}
