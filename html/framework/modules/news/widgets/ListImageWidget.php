<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 26.09.2017
 * Time: 13:18
 */

declare(strict_types = 1);


namespace app\modules\news\widgets;


use app\modules\news\helpers\File;
use app\modules\news\helpers\Path;
use app\modules\news\models\News;
use yii\base\Widget;
use yii\helpers\FileHelper;

class ListImageWidget extends Widget
{
    /** @var Path */
    public $path;

    public function init()
    {
        parent::init();
        $this->path = new Path(News::UPLOAD_DIRECTORY, '@root');
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $files = FileHelper::findFiles(
            $this->path->getDirectory(),
            [
                'except' => $this->getExcept(),
                'only' => ['*.png', '*.jpg'],
                'caseSensitive' => false,
            ]
        );

        $files = array_map(function ($file) {
            $path = clone $this->path;
            $path->setFile(new File(basename($file)));
            return $path;
        }, $files);

        return $this->render('image/list', ['files' => $files]);
    }

    /**
     * @return array
     */
    public function getExcept(): array
    {
        $except = [];
        foreach (News::THUMBS as $thumb => $options) {
            $except[] = "/$thumb-*";
        }
        return $except;
    }
}