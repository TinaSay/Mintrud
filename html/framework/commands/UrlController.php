<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.08.2017
 * Time: 10:53
 */

namespace app\commands;

use app\modules\directory\models\Directory;
use app\modules\document\models\Document;
use app\modules\ministry\models\Ministry;
use app\modules\news\models\News;
use Exception;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class UrlController extends Controller
{
    public function actionReplace()
    {
        ini_set('memory_limit', '200M');

        $this->document();
        $this->ministry();
        $this->news();
    }


    public function news()
    {
        $this->stdout('BEGIN ministry' . PHP_EOL);
        $models = News::find()->all();
        foreach ($models as $model) {
            $text = $this->replace($model->text, $model->id);
            Yii::$app->db->createCommand()
                ->update(News::tableName(), ['text' => $text], ['id' => $model->id])
                ->execute();
        }
    }

    public function ministry()
    {
        $this->stdout('BEGIN ministry' . PHP_EOL);
        $models = Ministry::find()->all();
        foreach ($models as $model) {
            if (!is_null($model->text)) {
                $this->stdout($model->url . PHP_EOL);
                $text = $this->replace($model->text, $model->id);
                Yii::$app->db->createCommand()->update(Ministry::tableName(), ['text' => $text], ['id' => $model->id])->execute();
            }
        }
    }


    public function document()
    {
        $this->stdout('BEGIN Document' . PHP_EOL);
        $models = Document::find()->all();
        foreach ($models as $model) {
            $this->stdout('ID: ' . $model->id . PHP_EOL);
            $directory = Directory::find()->id($model->directory_id)->one();
            $url = $directory->url . '/' . $model->url_id;
            Console::stdout($url . PHP_EOL);
            $model->text = $this->file($model->text, $url);
            $model->text = $this->replace($model->text, $model->id);
            Yii::$app
                ->db
                ->createCommand()
                ->update(Document::tableName(), ['text' => $model->text], ['id' => $model->id])
                ->execute();

        }
    }

    public function replace(string $text, $id): string
    {
        $text = preg_replace_callback(
            '~href="http://rosmintrud.ru~',
            function () use ($id) {
                Console::stdout('REPLACE URL ID:' . $id . PHP_EOL);
                return 'href="';
            },
            $text
        );
        $text = preg_replace_callback(
            '~href="http://www.rosmintrud.ru~',
            function () use ($id) {
                Console::stdout('REPLACE URL ID:' . $id . PHP_EOL);
                return 'href="';
            },
            $text
        );
        return $text;
    }

    public function file($text, $url): string
    {
        $text = preg_replace_callback(
            '~href="(.+?\.(docx?|pdf|pptx?|zip|xlsx?|rtf)(%20| )?)"~',
            function ($matches) use ($url) {
                if ($host = parse_url($matches['1'], PHP_URL_HOST)) {
                    if ($host != 'rosmintrud.ru' && $host != 'www.rosmintrud.ru') {
                        return $matches['1'];
                    }
                    $query = $matches['1'];
                } else {
                    if (strncasecmp($matches['1'], '/', 1) === 0) {
                        if (strncasecmp($matches['1'], '/uploads/', 9) === 0) {
                            return 'href="' . $matches['1'] . '"';
                        }
                        $query = 'http://www.rosmintrud.ru' . $matches['1'];
                    } else {
                        Console::stdout('compile: ' . PHP_EOL);
                        $query = 'http://www.rosmintrud.ru' . '/' . $url . '/' . $matches['1'];
                    }
                }
                Console::stdout('Download file: ' . $query . PHP_EOL);
                $extension = pathinfo($query, PATHINFO_EXTENSION);
                $name = hash('crc32', pathinfo($query, PATHINFO_BASENAME)) . '-' . time() . '.' . $extension;
                $path = Yii::getAlias('@public/magic/ru-RU/' . $name);
                $fp = fopen($path, 'w+');
                $ch = curl_init($query);
                curl_setopt($ch, CURLOPT_TIMEOUT, 1000);
                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                if (!curl_exec($ch)) {
                    throw new Exception();
                }
                if (curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
                    throw new Exception('CODE RESPONSE IS NOT 200');
                }
                curl_close($ch);
                fclose($fp);
                return 'href="/uploads/magic/ru-RU/' . $name . '"';
            },
            $text
        );
        return $text;
    }
}