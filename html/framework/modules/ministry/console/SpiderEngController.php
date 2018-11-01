<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 03.09.2017
 * Time: 15:37
 */

namespace app\modules\ministry\console;


use app\components\Spider;
use app\modules\ministry\models\Ministry;
use DateTime;
use IntlDateFormatter;
use phpQueryObject;
use yii\console\Controller;
use yii\httpclient\Client;

/**
 * Class SpiderEngController
 * @package app\modules\ministry\console
 */
class SpiderEngController extends Controller
{
    /**
     *
     */
    public function actionPull()
    {
        $urls = [
            'http://www.rosmintrud.ru/eng/ministry' => [
                'http://www.rosmintrud.ru/eng/ministry/structure',
                'http://www.rosmintrud.ru/eng/ministry/minister',
                'http://www.rosmintrud.ru/eng/ministry/international',
                'http://www.rosmintrud.ru/eng/ministry/contacts',
                'http://www.rosmintrud.ru/eng/ministry/8',
            ],
        ];
        foreach ($urls as $url => $articleUrls) {
            $this->stdout($url . PHP_EOL);
            $html = Spider::newDocument((new Client())->createRequest()->setUrl($url)->send());
            $title = trim(Spider::title($html)->text());
            $created = $this->created($html);
            $text = $html->find('.issue');
            $text->find('.keywords')->remove();
            $text->find('.create-date')->remove();
            $text = $text->__toString();
            $text = $this->text($text, $url);
            \Yii::$app
                ->db
                ->createCommand()
                ->insert(
                    '{{%ministry}}',
                    [
                        'title' => $title,
                        'type' => Ministry::TYPE_FOLDER,
                        'text' => $text,
                        'url' => Spider::getPath($url),
                        'depth' => 0,
                        'hidden' => Ministry::HIDDEN_NO,
                        'language' => 'en-US',
                        'layout' => '//common-eng',
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $created->format('Y-m-d'),
                    ]
                )->execute();
            $id = \Yii::$app
                ->db
                ->getLastInsertID();

            foreach ($articleUrls as $articleUrl) {
                $this->stdout('ARTICLE: ' . $articleUrl . PHP_EOL);
                $articleHtml = Spider::newDocument((new Client())->createRequest()->setUrl($articleUrl)->send());
                $articleTitle = trim(Spider::title($articleHtml)->text());
                $articleCreated = $this->created($articleHtml);
                $articleText = $articleHtml->find('.issue');
                $articleText->find('.keywords')->remove();
                $articleText->find('.create-date')->remove();
                $articleText = $articleText->__toString();
                $articleText = $this->text($articleText, $articleUrl);

                \Yii::$app
                    ->db
                    ->createCommand()
                    ->insert(
                        '{{%ministry}}',
                        [
                            'parent_id' => $id,
                            'title' => $articleTitle,
                            'type' => Ministry::TYPE_ARTICLE,
                            'text' => $articleText,
                            'url' => Spider::getPath($articleUrl),
                            'depth' => 1,
                            'hidden' => Ministry::HIDDEN_NO,
                            'language' => 'en-US',
                            'layout' => '//common-eng',
                            'created_at' => $articleCreated->format('Y-m-d'),
                            'updated_at' => $articleCreated->format('Y-m-d'),
                        ]
                    )->execute();
            }
        }
    }


    /**
     * @param phpQueryObject $html
     * @return DateTime
     */
    public function created(phpQueryObject $html): DateTime
    {
        $text = $html->find('.create-date')->text();
        $intl = new IntlDateFormatter('en-EN', null, null);
        $intl->setPattern('LLLL d yyyy');
        $position = 11;
        $timestamp = $intl->parse($text, $position);
        $created = (new DateTime())->setTimestamp($timestamp);
        return $created;
    }

    public function text(string $text, string $url)
    {
        $text = Spider::replaceUrl($text);
        return $text;
    }
}