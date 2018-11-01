<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.06.2017
 * Time: 15:23
 */

declare(strict_types = 1);


namespace app\modules\news\commands;


use app\modules\news\components\Thumb;
use Yii;
use yii\console\Controller;

class ThumbController extends Controller
{
    public $name;
    public $upload;

    private $thumbs = [
        '490x330' => [
            'width' => 490,
            'height' => 330,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
        ],
        '230x160' => [
            'width' => 230,
            'height' => 160,
            'quality' => 100,
            'mode' => \Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET,
        ],
    ];

    public function options($actionID)
    {
        return ['name', 'upload'];
    }

    public function optionAliases()
    {
        return ['n' => 'name', 'u' => 'upload'];
    }

    /**
     * @return int
     */
    public function actionGenerate(): int
    {
        $thumb = new Thumb($this->name, $this->upload, $this->thumbs);
        if (!$thumb->generates()) {
            return Controller::EXIT_CODE_ERROR;
        }
        return Controller::EXIT_CODE_NORMAL;
    }

    public function actionTest()
    {
        $command = str_replace('\\', '/', Yii::getAlias('@app/yii') . ' news/thumb/generate -n=' . '0a80a49b-1498372589.jpg' . ' -u=@public/news');
        echo $command;
        //exec('bash -c "' . $command . '"');
    }
}