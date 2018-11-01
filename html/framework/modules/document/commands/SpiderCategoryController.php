<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 20.07.2017
 * Time: 13:31
 */

declare(strict_types = 1);


namespace app\modules\document\commands;


use app\modules\directory\models\Directory;
use app\modules\directory\rules\type\TypeInterface;
use app\modules\document\models\spider\Spider;
use yii\console\Controller;

/**
 * Class SpiderCategoryController
 * @package app\modules\document\commands
 */
class SpiderCategoryController extends Controller
{
    /**
     * @return int
     */
    public function actionPull(): int
    {
        $spider = Spider::find()->orderBy(['id' => SORT_ASC])->each();
        foreach ($spider as $item) {
            $path = trim(parse_url($item->original)['path'], '/');
            $parts = explode('/', $path);
            array_pop($parts);
            $url = '';

            foreach ($parts as $part) {
                $url = $this->createDirectory($part, $url);
            }

        }
        return Controller::EXIT_CODE_NORMAL;
    }

    /**
     * @param string $part
     * @param string $parts
     * @return string
     */
    public function createDirectory(string $part, string $parts = ''): string
    {
        $parentDirectory = null;
        if ($parts !== '') {
            $parentDirectory = Directory::find()->url($parts)->type(TypeInterface::TYPE_DOC)->limit(1)->one();
            if (is_null($parentDirectory)) {
                throw new \RuntimeException('not parent');
            }
            $parentDirectory = $parentDirectory->id;
            $parts .= '/' . $part;
        } else {
            $parts = $part;
        }
        $directory = Directory::find()->url($parts)->type(TypeInterface::TYPE_DOC)->limit(1)->one();
        if (is_null($directory)) {
            $directory = Directory::create(TypeInterface::TYPE_DOC, $part, $part, $parentDirectory);
            $directory->detachBehavior('CreatedByBehavior');
            if (!$directory->save()) {
                var_dump($directory->getErrors());
                var_dump($parts);
                throw new \RuntimeException('Filed to save');
            }
        }
        return $parts;
    }
}