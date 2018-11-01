<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 12.09.2017
 * Time: 15:34
 */

namespace app\modules\news\commands;


use app\modules\news\models\spider\Spider;
use yii\console\Controller;
use yii\db\Exception;
use yii\httpclient\Client;

class PullController extends Controller
{
    public function actionRun()
    {
        $spiders = Spider::find()->andWhere(['is_parsed' => Spider::IS_PARSED_NO])->all();
        foreach ($spiders as $spider) {
            $this->stdout($spider->url . PHP_EOL);
            $response = (new Client())->createRequest()->setUrl($spider->url)->send();
            if (!$response->isOk) {
                throw new \RuntimeException('sending error');
            }
            $html = \app\components\Spider::newDocument($response);
            $title = \app\components\Spider::title($html)->text();
            $created = $this->getDateCreate($html);
            $updated = $this->getDateUpdate($html);
            $text = $html->find('.issue')->__toString();
            $text = $this->text($text);
            $did = \Yii::$app->db->createCommand()
                ->insert(
                    '{{%news}}',
                    [
                        'directory_id' => $spider->directory_id,
                        'url_id' => $spider->url_id,
                        'title' => $title,
                        'text' => $text,
                        'date' => $created->format('Y-m-d'),
                        'created_at' => $created->format('Y-m-d'),
                        'updated_at' => $updated->format('Y-m-d')
                    ]
                )->execute();

            if ($did !== 1) {
                throw new \RuntimeException('executing error');
            }
            $spider->is_parsed = $spider::IS_PARSED_YES;
            if (!$spider->save()) {
                throw new Exception('Saving error');
            }
        }
    }

    public function text(string $text)
    {
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
}