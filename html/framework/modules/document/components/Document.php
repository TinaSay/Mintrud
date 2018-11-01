<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.07.2017
 * Time: 9:49
 */

declare(strict_types = 1);


namespace app\modules\document\components;

use app\components\ConsoleExec;
use app\modules\directory\models\Directory;
use app\modules\document\models\Direction;
use app\modules\document\models\Document as DocumentModel;
use app\modules\document\models\spider\File;
use app\modules\document\models\spider\Spider;
use app\modules\organ\models\Organ;
use app\modules\typeDocument\models\Type;
use IntlDateFormatter;
use phpQueryObject;
use yii\helpers\Console;


/**
 * Class Document
 * @package app\modules\document\components
 */
class Document extends BaseDocument
{


    /** @var Spider */
    public $spider;

    /**
     * @var array
     */
    public $mapOrgan = [
        'court' => 'Верховный суд Российской Федерации',
        'mzsr' => 'Минздравсоцразвития России',
        'mintrud' => 'Минтруд России',
        'pfrf' => 'Пенсионный фонд Российской Федерации',
        'government' => 'Правительство РФ',
        'president' => 'Президент РФ',
        'rights' => 'Федеральная служба по надзору в сфере защиты прав потребителей и благополучия человека',
        'development' => 'Федеральная служба по надзору в сфере здравоохранения и социального развития',
        'work' => 'Федеральная служба по труду и занятости',
        'high-tech' => 'Федеральное агентство по высокотехнологичной медицинской помощи',
        'roszdrav' => 'Федеральное агентство по здравоохранению и социальному развитию',
        'med' => 'Федеральное медико-биологическое агентство',
        'ffoms' => 'Федеральный Фонд обязательного медицинского страхования',
        'fss' => 'Фонд социального страхования Российской Федерации',
    ];

    public $mapDirection = [
        'nok' => 'Независимая оценка квалификации',
        'relationship' => 'Социальное партнерство и трудовые отношения',
        'public-service' => 'Государственная гражданская служба',
        'partnership' => 'Социальное партнерство',
        'safety' => 'Охрана труда',
        'salary' => 'Оплата труда',
        '21' => 'Оплата труда бюджетников',
        '20' => 'Профессиональные стандарты',
        '15' => 'Профессиональное образование',
        'protection' => 'Защита прав трудящихся',
        'cooperation' => 'Международное сотрудничество в сфере трудовых отношений',
        'alternative-service' => 'Альтернативная гражданская служба',

        'employment' => 'Рынок труда',
        'budjet' => 'Занятость населения в бюджетной сфере',
        'migration' => 'Трудовая миграция',
        'resettlement' => 'Трудоустройство людей с ограниченными возможностями',

        'nsok' => 'Независимая система оценки качества',
        'living-standard' => 'Уровень жизни и доходов населения',
        'service' => 'Социальное обслуживание граждан',
        'insurance' => 'Социальное страхование',
        'invalid-defence' => 'Социальная защита инвалидов',
        'vetaran-defence' => 'Социальная защита пожилых',
        'force-majeur' => 'Социальная защита граждан пострадавших в результате чрезвычайных ситуаций',
        'family' => 'Социальная политика в отношении семьи женщин и детей',
        'demography' => 'Демографическая политика',
        'social' => 'Социальная политика',
        'fund-children' => 'Фонд поддержки детей находящихся в трудной жизненной ситуации',
        'cooperation_social' => 'Международное сотрудничество в социальной сфере',

        'increase' => 'Увеличение пенсий',
        'pension' => 'Назначение и выплата пенсий',
        'insurance_pensions' => 'Пенсионное страхование',
        'indexing' => 'Индексация пенсий',
        'financing' => 'Формирование пенсионных накоплений',
        'chastnoe' => 'Негосударственное пенсионное обеспечение',
        'razvitie' => 'Совершенствование пенсионной системы',
        'cooperation_pensions' => 'Международное сотрудничество в сфере пенсионного обеспечения'
    ];

    /**
     * @var ConsoleExec
     */
    public $console;

    public function __construct(phpQueryObject $document)
    {
        parent::__construct($document);
        $this->console = new ConsoleExec();
    }

    /**
     * @param callable $function
     * @throws \Exception
     */
    public function saveDocument(callable $function): void
    {
        $document = $this->document->find('div.story.issue');

        $document->find('div.path')->remove();

        $document->find('h1.title a')->remove();
        $title = $document->find('h1.title')->text();
        $document->find('h1.title')->remove();

        $number = null;

        if (preg_match('~№\b(.*?) ~', $title, $matches)) {
            $number = $matches['1'];
        }
        $announce = $document->find('h2.doc-title')->text();
        if (empty($announce)) {
            $announce = $title;
        }
        $document->find('h2.doc-title')->remove();

        $dates = $document->find('p.create-date')->text();
        $document->find('p.create-date')->remove();

        $created = null;
        $updated = null;
        if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches)) {
            $created = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0'])->format('Y-m-d H:s');
            $updated = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['1'])->format('Y-m-d H:s');
        }
        $text = $document->removeClass('story')->removeClass('issue');

        $minust = $document->find('p.minust');

        $dateMinust = null;
        $numberMinust = null;

        if ($minust->length == 1) {
            $textMinust = $minust->text();
            $document->find('p.minust')->remove();

            if (preg_match('~^Зарегистрирован в Минюсте (\d?\d\b.+?\b\d\d\d\d)~', $textMinust, $matches)) {
                $intl = new IntlDateFormatter('ru_RU', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
                $intl->setPattern('dd MMMM yyyy');
                $dateMinust = (new \DateTime())->setTimestamp($intl->parse($matches[1]))->format('Y-m-d');
                if (!$dateMinust) {
                    throw new \RuntimeException('p.minust');
                }
                if (preg_match('~№ (\d+)$~', $textMinust, $matches)) {
                    $numberMinust = $matches[1];
                    if (!$numberMinust) {
                        throw new \RuntimeException('p.minust');
                    }
                }

            } else {
                throw new \RuntimeException('p.minust');
            }
        }
        $this->getDirectionIds();
        /** @var DocumentModel $documentModel */
        $documentModel = $function(
            $this->getDirectoryId(),
            $this->getTypeDocumentId(),
            $this->getDirectionIds(),
            $title,
            $announce,
            $text->__toString(),
            $this->getId(),
            $this->getOrganId(),
            $dateMinust,
            $numberMinust,
            $number,
            $created,
            $updated
        );
        $documentModel->detachBehavior('CreatedByBehavior');
        $documentModel->detachBehavior('TimestampBehavior');

        try {
            if (!$documentModel->save()) {
                var_dump($documentModel->errors);
                throw new \RuntimeException('Filed to save');
            }
            foreach ($this->spider->files as $file) {
                Console::stdout($file->origin . PHP_EOL);
                $this->console->bash('document/spider-document/pull-file -if=' . $file->id . ' -idoc=' . $documentModel->id);
            }
        } catch (\Exception $e) {
            $documentModel->delete();
            throw $e;
        }
    }

    /**
     * @param Spider $spider
     */
    public function setSpider(Spider $spider): void
    {
        $this->spider = $spider;
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        $parts = explode('/', $this->spider->original);
        $id = array_pop($parts);
        if (!is_numeric($id)) {
            return null;
        }
        return (int)$id;
    }


    /**
     * @return int
     */
    public function getDirectoryId(): int
    {
        $rawUrl = parse_url($this->spider->original);
        $path = $rawUrl['path'];
        $parts = explode('/', $path);
        array_pop($parts);
        $url = trim(implode('/', $parts), '/');
        return Directory::find()->url($url)->one()->id;
    }

    /**
     * @return int
     */
    public function getTypeDocumentId(): int
    {
        return Type::find()->title($this->spider->type_document)->one()->id;
    }

    /**
     * @return int|null
     */
    public function getOrganId(): ?int
    {
        if (is_null($this->spider->organ)) {
            return null;
        }
        $organ = Organ::find()->title($this->mapOrgan[$this->spider->organ])->one();
        if (is_null($organ)) {
            throw new \RuntimeException('not organ');
        }
        return $organ->id;
    }

    public function getDirectionIds(): ?array
    {
        $rawDirections = $this->spider->theme;
        if (is_null($rawDirections)) {
            return null;
        }
        $names = explode(',', $rawDirections);
        $result = [];
        foreach ($names as $name) {
            $id = Direction::find()->title($this->mapDirection[$name])->limit(1)->one()->id;
            $result[] = $id;
        }
        return $result;
    }

    /**
     * @param int $id
     */
    public function saveFiles(int $id): void
    {
        $li = $this->document->find('div.download ul.ib li');
        foreach ($li as $item) {
            $href = pq($item)->find('a')->attr('href');
            Console::stdout($href . PHP_EOL);
            $title = pq($item)->find('a')->text();
            $file = File::create($id, $href, $title);
            $file->save();
        }
    }
}