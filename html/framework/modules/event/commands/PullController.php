<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 13.09.2017
 * Time: 10:26
 */

namespace app\modules\event\commands;


use app\components\Spider;
use app\modules\event\models\spider\EventSpider;
use yii\console\Controller;
use yii\db\Exception;
use yii\httpclient\Client;

class PullController extends Controller
{
    public function actionRun()
    {
        $spiders = EventSpider::find()->andWhere(['is_parsed' => EventSpider::IS_PARSED_NO])->all();
        foreach ($spiders as $spider) {
            $this->stdout($spider->url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($spider->url)->send();
            if (!$response->isOk) {
                throw new \RuntimeException('sending error');
            }
            $html = Spider::newDocument($response);
            $title = Spider::title($html)->text();
            $created = $this->getDateCreate($html);
            $place = $this->getPlace($html);
            $dates = $this->getDates($html);
            $text = $html->find('.issue')->__toString();
            $text = $this->text($text);
            $did = \Yii::$app->db->createCommand()
                ->insert(
                    '{{%event}}',
                    [
                        'id' => $spider->url_id,
                        'title' => $title,
                        'text' => $text,
                        'place' => $place,
                        'date' => $created->format('Y-m-d'),
                        'begin_date' => $dates[0]->format('Y-m-d'),
                        'language' => 'ru-RU',
                        'finish_date' => isset($dates[1]) ? $dates[1]->format('Y-m-d') : null,
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $created->format('Y-m-d'),
                    ]
                )->execute();

            if ($did !== 1) {
                throw new \RuntimeException('executing error');
            }
            $spider->is_parsed = $spider::IS_PARSED_YES;
            if (!$spider->save()) {
                throw new Exception('saving error');
            }
        }
    }

    public function text(string $text)
    {
        $text = Spider::replaceUrl($text);
        return $text;
    }

    public function getDates(\phpQueryObject $html)
    {
        $text = trim($html->find('.publishedby.city-dark')->text());
        if (($pos = strrpos($text, ',')) !== false) {
            $dates = substr($text, 0, $pos);
        } else {
            $dates = $text;
        }
        $intl = new \IntlDateFormatter('ru-RU', null, null);
        $intl->setPattern('d MMMM yyyy');
        if (preg_match('~(\d{1,2})-(\d{1,2}) ([^ ]+)~', $dates, $matches)) {
            $monthAndYear = $matches['3'] . ' 2017';
            $dateBegin = (new \DateTime())->setTimestamp($intl->parse($matches['1'] . ' ' . $monthAndYear));
            $dateFinish = (new \DateTime())->setTimestamp($intl->parse($matches['2'] . ' ' . $monthAndYear));
            return [$dateBegin, $dateFinish];
        } else {
            $dateBegin = (new \DateTime())->setTimestamp($intl->parse(trim($dates) . ' 2017'));
            return [$dateBegin];
        }
    }

    public function getPlace(\phpQueryObject $html): ?string
    {
        $text = $html->find('.publishedby.city-dark')->text();
        if (($pos = strrpos($text, ',')) !== false) {
            return trim(substr($text, $pos + 1));
        } else {
            return null;
        }
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
}