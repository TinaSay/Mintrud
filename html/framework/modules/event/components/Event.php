<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 04.08.2017
 * Time: 17:47
 */

// declare(strict_types=1);


namespace app\modules\event\components;


use app\modules\event\models\Event as EventModel;
use app\modules\event\models\spider\Spider;
use DateTime;
use IntlDateFormatter;
use Yii;
use yii\helpers\Console;

class Event extends BaseEvent
{
    /**
     * @var Spider
     */
    private $spider;

    /**
     *
     */
    public function pull()
    {
        $id = $this->getId();
        $model = EventModel::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!is_null($model)) {
                Console::stdout('The event exists' . PHP_EOL);
                $model->editSpider(
                    $this->title(),
                    $this->text(),
                    $this->date(),
                    $this->place(),
                    $this->getBeginDate(),
                    $this->getFinishDate()
                );
            } else {
                Console::stdout('The event does not exist' . PHP_EOL);
                $model = EventModel::createSpider(
                    $id,
                    $this->title(),
                    $this->text(),
                    $this->date(),
                    $this->place(),
                    $this->getBeginDate(),
                    $this->getFinishDate()
                );
            }
            if (!$model->save()) {
                throw new \RuntimeException('Saving error');
            }
            $spider = $this->getSpider();
            $spider->is_parsed = Spider::IS_PARSED_YES;
            if (!$spider->save()) {
                throw new \RuntimeException('Saving error');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
        Console::stdout('Pulling success' . PHP_EOL);
        $transaction->commit();
    }

    /**
     * @param Spider $spider
     */
    public function setSpider(Spider $spider): void
    {
        $this->spider = $spider;
    }


    /**
     * @return Spider
     */
    public function getSpider(): Spider
    {
        return $this->spider;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        $url = $this->getSpider()->url;
        $url = trim($url, '/');
        $parts = explode('/', $url);
        $id = array_pop($parts);
        if (!is_numeric($id)) {
            throw new \RuntimeException('Not id');
        }
        return (int)$id;
    }

    public function text(): string
    {
        $story = $this->document->find('div.story');
        if (empty($story)) {
            throw new \RuntimeException('story pulling error');
        }
        $issue = $story->find('div.issue');
        return $issue->__toString();
    }

    public function title(): string
    {
        $story = $this->document->find('div.story');
        $h1 = $story->find('h1.title');
        $title = $h1->text();
        if (empty($title)) {
            throw new \RuntimeException('title pulling error');
        }
        return $title;
    }


    /**
     * @return DateTime
     */
    public function date(): DateTime
    {
        $story = $this->document->find('div.story');
        $createDate = $story->find('p.create-date');
        $createDateText = $createDate->text();

        if (empty($createDateText)) {
            throw new \RuntimeException('createDate pulling error');
        }
        if (preg_match_all('~(\d\d:\d\d, \d\d\.\d\d\.\d\d\d\d)~', $createDateText, $matches)) {
            $date = DateTime::createFromFormat('H:i\, d.m.Y', $matches['0']['0']);
        } else {
            throw new \RuntimeException('createDate parsing error');
        }
        return $date;
    }

    public function place(): ?string
    {
        $story = $this->document->find('div.story');

        $publishedbyCityDark = $story->find('p.publishedby.city-dark');
        $publishedbyCityDarkText = strip_tags($publishedbyCityDark->text());

        if (empty($publishedbyCityDarkText)) {
            throw new \RuntimeException('publishedbyCityDark pulling error');
        }
        $eventAndPlace = explode(',', $publishedbyCityDarkText);
        if (isset($eventAndPlace['1'])) {
            return trim($eventAndPlace['1']);
        }
        return null;
    }

    public function rangeDate()
    {
        $story = $this->document->find('div.story');
        $year = $this->date()->format('Y');
        $publishedbyCityDark = $story->find('p.publishedby.city-dark');
        $publishedbyCityDarkText = $publishedbyCityDark->text();
        if (empty($publishedbyCityDarkText)) {
            throw new \RuntimeException('publishedbyCityDark pulling error');
        }
        $eventAndPlace = explode(',', $publishedbyCityDark);
        if (empty($eventAndPlace['0'])) {
            throw new \RuntimeException('date parsing error');
        }
        if (preg_match('~\d\d?.\d\d?.+~', $eventAndPlace['0'], $match)) {
            $match[0] = str_replace(' ', ' ', $match[0]);
            $d_dM = explode(' ', $match[0]);
            if (!isset($d_dM[0]) || !isset($d_dM[1])) {
                throw new \RuntimeException('d_dM');
            }
            $ds = explode('-', $d_dM[0]);
            if (!isset($ds[0]) || !isset($ds[1])) {
                return null;
            }
            $intl = new IntlDateFormatter('ru_RU', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
            $intl->setPattern('MMMM');
            $month = (new DateTime())->setTimestamp($intl->parse($d_dM['1']));
            $beginDate = (new DateTime())->setDate($year, $month->format('m'), $ds['0']);
            $finishDate = (new DateTime())->setDate($year, $month->format('m'), $ds['1']);
            return [$beginDate, $finishDate];
        } elseif (preg_match('~\d\d?.+?-\d\d?.+~', $eventAndPlace['0'], $match)) {
            return null;
        } elseif (preg_match('~\d\d?.+~', $eventAndPlace['0'], $match)) {
            $match[0] = str_replace(' ', ' ', $match[0]);
            trim($match[0]);
            $intl = new IntlDateFormatter('ru_RU', IntlDateFormatter::NONE, IntlDateFormatter::NONE);
            $intl->setPattern('dd MMMM');
            $dayMonth = (new DateTime())->setTimestamp($intl->parse($match['0']));
            $beginDate = (new DateTime())->setDate($year, $dayMonth->format('m'), $dayMonth->format('d'));
            return $beginDate;
        } elseif (preg_match('~(.+-.+)~', $eventAndPlace['0'], $match)) {
            return null;
        } else {
            throw new \RuntimeException('event date');
        }
    }


    /**
     * @return DateTime|null
     */
    public function getBeginDate(): ?DateTime
    {
        $dates = $this->rangeDate();
        if (is_null($dates)) {
            return null;
        }
        if ($dates instanceof DateTime) {
            return $dates;
        }
        return $dates['0'];
    }

    /**
     * @return DateTime|null
     */
    public function getFinishDate(): ?DateTime
    {
        $dates = $this->rangeDate();
        if (is_null($dates)) {
            return null;
        }
        if ($dates instanceof DateTime) {
            return null;
        }
        return $dates['1'];
    }
}