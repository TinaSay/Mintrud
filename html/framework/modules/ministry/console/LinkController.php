<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 13.09.2017
 * Time: 13:20
 */

namespace app\modules\ministry\console;


use app\modules\ministry\models\Ministry;
use yii\console\Controller;

class LinkController extends Controller
{
    public function actionRun()
    {
        $ministries = Ministry::find()->all();
        foreach ($ministries as $ministry) {
            $this->getUrls($ministry->text, $ministry->url);
        }
    }

    public function getUrls($text, $parseUrl): void
    {
        preg_match_all('~href="([^"]+)"~', $text, $match);
        foreach ($match['1'] as $url) {
            if (strncasecmp($url, '/docs', '4') === 0) {
                $name = basename($url . PHP_EOL);
                if (strncasecmp($name, '0', 1) === 0) {
                    $this->stdout($parseUrl . ' : ' . $url . PHP_EOL);
                }
            }
        }
    }
}