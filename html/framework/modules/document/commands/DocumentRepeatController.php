<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 10.09.2017
 * Time: 11:17
 */

declare(strict_types=1);

namespace app\modules\document\commands;


use app\modules\directory\models\Directory;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\Document;
use app\modules\document\models\repository\DescriptionDirectoryRepository;
use app\modules\document\models\repository\DirectionRepository;
use app\modules\document\models\spider\File;
use app\modules\document\models\spider\Spider;
use app\modules\organ\models\Organ;
use app\modules\organ\models\repositories\OrganRepository;
use app\modules\typeDocument\models\repositories\TypeRepository;
use app\modules\typeDocument\models\Type;
use Exception;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use yii\helpers\FileHelper;
use yii\httpclient\Client;

class DocumentRepeatController extends Controller
{
    /**
     * @var TypeRepository
     */
    private $typeRepository;
    /**
     * @var OrganRepository
     */
    private $organRepository;
    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;
    /**
     * @var DescriptionDirectoryRepository
     */
    private $descriptionDirectoryRepository;
    /**
     * @var DirectionRepository
     */
    private $directionRepository;

    public function __construct(
        $id,
        Module $module,
        TypeRepository $typeRepository,
        OrganRepository $organRepository,
        DirectoryRepository $directoryRepository,
        DescriptionDirectoryRepository $descriptionDirectoryRepository,
        DirectionRepository $directionRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->typeRepository = $typeRepository;
        $this->organRepository = $organRepository;
        $this->directoryRepository = $directoryRepository;
        $this->descriptionDirectoryRepository = $descriptionDirectoryRepository;
        $this->directionRepository = $directionRepository;
    }


    public function actionRun()
    {
        $spider = Spider::find()->isParsed()->all();
        foreach ($spider as $item) {
            if (in_array($item->original, [
                'http://www.rosmintrud.ru/docs/mintrud/analytics/6',
                'http://www.rosmintrud.ru/docs/mintrud/orders/4',
            ])) {
                continue;
            }
            $html = \app\components\Spider::newDocument((new Client())->createRequest()->setUrl($item->original)->send());
            $title = $this->getTitle($html);
            $announce = $html->find('.doc-title')->text();
            $created = $this->getDateCreate($html);
            $updated = $this->getDateUpdate($html);
            $text = $this->getText($html);
            $type = $this->getType($item);
            $organ = $this->getOrgan($item);
            $directory = $this->getDirectory($item);
            $urlId = $this->getUrlId($item);
            $number = $this->getNumber($title);

            if (is_null($type)) {
                continue;
            }

            echo $item->original . PHP_EOL;
            $did = Yii::$app
                ->db
                ->createCommand()
                ->insert(
                    '{{%document}}',
                    [
                        'url_id' => $urlId,
                        'directory_id' => $directory->id,
                        'type_document_id' => $type->id,
                        'organ_id' => is_null($organ) ? null : $organ->id,
                        'title' => $title,
                        'announce' => $announce,
                        'text' => $text,
                        'number' => $number,
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $updated->format('Y-m-d'),
                    ]
                )
                ->execute();

            if ($did !== 1) {
                throw new Exception();
            }

            $id = Yii::$app->db->getLastInsertID();

            $this->createRelation($item, (int)$id);
            $this->downloadFile($item, (int)$id);
            $item->is_parsed = $item::IS_PARSED_YES;
            if (!$item->save()) {
                throw new Exception();
            }
        }
    }

    public function downloadFile(Spider $model, int $documentId)
    {
        $spiderFiles = File::find()
            ->andWhere([File::tableName() . '.[[spider_id]]' => $model->id])
            ->andWhere([File::tableName() . '.[[is_parsed]]' => Spider::IS_PARSED_NO])
            ->all();

        foreach ($spiderFiles as $spiderFile) {
            $url = $spiderFile->origin;
            $extension = pathinfo($url, PATHINFO_EXTENSION);
            $name = hash('crc32', pathinfo($url, PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
            $path = \Yii::getAlias('@root/uploads/magic/ru-RU') . '/' . $name;
            $file = fopen($path, 'w+');
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1000);
            curl_setopt($curl, CURLOPT_FILE, $file);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_exec($curl);
            $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($code !== 200) {
                throw new Exception();
            }
            curl_close($curl);
            fclose($file);
            Yii::$app->db->refresh();
            $did = Yii::$app->db->createCommand()
                ->insert(
                    '{{%magic}}',
                    [
                        'module' => Document::class,
                        'record_id' => $documentId,
                        'label' => $spiderFile->title,
                        'src' => $name,
                        'size' => filesize($path),
                        'extension' => $extension,
                        'mime' => FileHelper::getMimeTypeByExtension($path),
                        'language' => 'ru-RU',
                        'created_at' => (new \DateTime())->format('Y-m-d'),
                        'updated_at' => (new \DateTime())->format('Y-m-d'),
                    ]
                )->execute();

            if ($did !== 1) {
                throw new Exception();
            }
            $spiderFile->is_parsed = Spider::IS_PARSED_YES;
            if (!$spiderFile->save()) {
                throw new Exception();
            }
        }
    }

    public function getNumber(string $title): ?string
    {
        $number = null;

        if (preg_match('~№\b(.*?) ~', $title, $matches)) {
            $number = $matches['1'];
        }
        return $number;
    }

    public function getUrlId(Spider $model): int
    {
        $url = $model->original;
        $parts = explode('/', $url);
        $id = array_pop($parts);
        return (int)$id;
    }

    public function createRelation(Spider $model, int $documentId): void
    {
        $direction = $model->direction;
        if (is_null($direction)) {
            return;
        }
        $directions = [
            'labour' => 'Трудовые отношения',
            'employment' => 'Занятость населения',
            'social' => 'Социальная защита',
            'pensions' => 'Пенсионное обеспечение',
        ];
        if (!isset($directions[$direction])) {
            throw new Exception();
        }
        $directory = $this->directoryRepository->findOneByTypeTitle(TypeInterface::TYPE_DESCRIPTION_DIRECTORY, $directions[$direction]);
        $this->directoryRepository->notFoundException($directory);
        $description = $this->descriptionDirectoryRepository->findOneByDirectory($directory->id);
        $this->descriptionDirectoryRepository->notFoundException($description);
        $themes = $model->theme;
        if (is_null($themes)) {
            return;
        }
        foreach (explode(',', $themes) as $theme) {
            $this->createRelationTheme($theme, $description->id, $documentId);
        }
    }

    public function createRelationTheme(string $theme, int $descriptionId, int $documentId): void
    {
        $themes = [
            '1' => [
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
            ],
            '2' => [
                'employment' => 'Рынок труда',
                'budjet' => 'Занятость населения в бюджетной сфере',
                'migration' => 'Трудовая миграция',
                'resettlement' => 'Трудоустройство людей с ограниченными возможностями',
                'cooperation' => 'Международное сотрудничество в сфере занятости',
            ],
            '3' => [
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
                'cooperation' => 'Международное сотрудничество в социальной сфере',
            ],
            '4' => [
                'increase' => 'Увеличение пенсий',
                'pension' => 'Назначение и выплата пенсий',
                'insurance' => 'Пенсионное страхование',
                'indexing' => 'Индексация пенсий',
                'financing' => 'Формирование пенсионных накоплений',
                'chastnoe' => 'Негосударственное пенсионное обеспечение',
                'razvitie' => 'Совершенствование пенсионной системы',
                'cooperation' => 'Международное сотрудничество в сфере пенсионного обеспечения',
            ]
        ];

        if (!isset($themes[$descriptionId][$theme])) {
            throw new Exception();
        }
        $direction = $this->directionRepository->findOneByDescriptionTitle($descriptionId, $themes[$descriptionId][$theme]);
        $this->directionRepository->notFoundException($direction);

        $did = Yii::$app->db->createCommand()
            ->insert(
                '{{%document_document_direction}}',
                [
                    'document_id' => $documentId,
                    'document_direction_id' => $direction->id,
                ]
            )->execute();

        if ($did !== 1) {
            throw new Exception();
        }
    }

    public function getDirectory(Spider $model): Directory
    {
        $url = $model->original;
        $path = parse_url($url, PHP_URL_PATH);
        $parts = explode('/', $path);
        array_pop($parts);
        $path = implode('/', $parts);
        $directory = $this->directoryRepository->findOneByUrl(trim($path, '/'));
        $this->directoryRepository->notFoundException($directory);
        return $directory;
    }

    public function getOrgan(Spider $model): ?Organ
    {
        if (is_null($model->organ)) {
            return null;
        }
        $organ = [
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
        if (!isset($organ[$model->organ])) {
            throw new Exception();
        }
        $title = $organ[$model->organ];
        $organModel = $this->organRepository->findOneByTitle($title);
        $this->organRepository->notFoundException($organModel);
        return $organModel;
    }

    public function getType(Spider $model): ?Type
    {
        if (is_null($model->type_document)) {
            return null;
        }
        $type = $this->typeRepository->findOneByTitle($model->type_document);
        $this->typeRepository->notFoundException($type);
        return $type;
    }

    public function getText(\phpQueryObject $html): string
    {
        $text = $html->find('.story.issue');
        $text->find('p.create-date')->remove();
        $text->find('.path')->remove();
        $text->find('.title')->remove();
        $text->find('.doc-title')->remove();
        $text = $text->__toString();
        $text = \app\components\Spider::replaceUrl($text);
        return $text;
    }

    public function getDateCreate(\phpQueryObject $html): \DateTime
    {
        $dates = $html->find('p.create-date')->text();
        preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches);
        $created = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
        return $created;
    }

    public function getDateUpdate(\phpQueryObject $html): ?\DateTime
    {
        $dates = $html->find('p.create-date')->text();
        preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $dates, $matches);
        if (!isset($matches['0']['1'])) {
            return null;
        }
        $updated = \DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['1']);
        return $updated;
    }

    public function getTitle(\phpQueryObject $html): string
    {
        $title = $html->find('.title');
        $title->find('a')->remove();
        $title = $title->text();
        return $title;
    }
}