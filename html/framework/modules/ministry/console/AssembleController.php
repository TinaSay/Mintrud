<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2017
 * Time: 10:06
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use app\modules\ministry\models\repositories\MinistryRepository;
use phpQueryObject;
use RuntimeException;
use yii\base\Module;
use yii\console\Controller;
use yii\db\Exception;
use yii\httpclient\Client;

class AssembleController extends Controller
{
    /**
     * @var MinistryRepository
     */
    private $ministryRepository;

    public function __construct(
        $id,
        Module $module,
        MinistryRepository $ministryRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->ministryRepository = $ministryRepository;
    }


    public function actionUrl()
    {
        \app\modules\ministry\models\spider\Spider::deleteAll();

        for ($i = 1; $i <= 8101; $i += 10) {
            $url = "http://www.rosmintrud.ru/find/extended/?order=down&start=$i";
            $this->stdout($url . PHP_EOL);
            $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
            $this->a($html);
        }

    }

    public function a(phpQueryObject $html)
    {
        $a = $html->find('.i-list a');
        foreach ($a as $item) {
            $href = pq($item)->attr('href');
            if (parse_url($href, PHP_URL_HOST)) {
                $this->save($href);
            }
        }
    }

    public function save(string $href)
    {
        $path = trim(parse_url($href, PHP_URL_PATH), '/');
        $ministry = $this->ministryRepository->findOneByUrl($path);
        if (!is_null($ministry)) {
            return;
        }
        $folderMinistry = $this->getFolder($path);
        if (is_null($folderMinistry)) {
            return;
        }
        $spider = new \app\modules\ministry\models\spider\Spider();
        $spider->url = $href;
        $spider->folder_id = $folderMinistry->id;
        if (!$spider->save()) {
            var_dump($spider->getErrors());
            throw new Exception('saving error');
        }
    }

    public function getFolder(string $path): ?Ministry
    {
        $folders = [
            'ministry/about',
            'ministry/programms',
            'ministry/opengov',
            'ministry/govserv',
            'ministry/anticorruption',
            'ministry/tenders',
            'ministry/budget',
            'ministry/inter',
            'zarplata',
            'reception',
            'nsok',
            'sovet',
        ];


        if (strncasecmp('ministry/about/structure', $path, strlen('ministry/about/structure')) === 0) {
            return null;
        }

        foreach ($folders as $folder) {
            if (strncasecmp($folder, $path, strlen($folder)) === 0) {
                $ministry = $this->ministryRepository->findOneByUrl($folder);
                $this->ministryRepository->notFoundException($ministry);
                return $ministry;
            }
        }
        return null;
    }

    public function actionPull()
    {
        $spiders = \app\modules\ministry\models\spider\Spider::find()->andWhere(['is_parsed' => \app\modules\ministry\models\spider\Spider::IS_PARSED_NO])->all();

        foreach ($spiders as $spider) {
            if (in_array($spider->url, [
                    'http://www.rosmintrud.ru/reception/help/tags',
                    'http://www.rosmintrud.ru/reception/form'
                ]
            )) {
                continue;
            }
            $this->stdout($spider->url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($spider->url)->send();
            if (!$response->isOk) {
                throw new RuntimeException('Sending error');
            }
            $html = Spider::newDocument($response);
            $title = Spider::title($html)->text();
            $created = $this->getDateCreate($html);
            $updated = $this->getDateUpdate($html);
            $text = Spider::text($html);
            $text = $text->__toString();
            $text = $this->text($text, $spider->url);

            $did = \Yii::$app->db->createCommand()
                ->insert('{{%ministry}}', [
                    'parent_id' => $spider->folder->id,
                    'title' => $title,
                    'type' => Ministry::TYPE_ARTICLE,
                    'text' => $text,
                    'url' => trim(parse_url($spider->url, PHP_URL_PATH), '/'),
                    'depth' => 1,
                    'language' => 'ru-RU',
                    'hidden' => Ministry::HIDDEN_NO,
                    'created_at' => $created->format('Y-m-d'),
                    'updated_at' => $updated->format('Y-m-d')
                ])->execute();

            if ($did !== 1) {
                throw new RuntimeException('executing error');
            }
            $spider->is_parsed = $spider::IS_PARSED_YES;
            if (!$spider->save()) {
                throw new Exception('saving error');
            }
        }
    }

    public function text(string $text, string $url)
    {
        $text = Spider::file($text, $url);
        $text = Spider::replaceUrl($text);
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

    public function actionDelete()
    {
        $spiders = \app\modules\ministry\models\spider\Spider::find()->all();

        foreach ($spiders as $spider) {
            \Yii::$app->db->createCommand()->delete(
                '{{%ministry}}',
                [
                    'url' => trim(parse_url($spider->url, PHP_URL_PATH), '/')
                ]
            )->execute();
            $spider->is_parsed = $spider::IS_PARSED_NO;

            if (!$spider->save()) {
                throw new Exception('saving error');
            }
        }
    }
}