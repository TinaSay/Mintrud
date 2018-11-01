<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.08.2017
 * Time: 15:27
 */

declare(strict_types = 1);


namespace app\modules\news\widgets;


use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\document\models\DescriptionDirectory;
use app\modules\news\models\repository\NewsRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

class NewsCountDescriptionWidget extends Widget
{
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    /** @var  DescriptionDirectory */
    public $description;

    public function __construct(
        NewsRepository $newsRepository,
        array $config = [])
    {
        parent::__construct($config);
        $this->newsRepository = $newsRepository;
    }

    public function init()
    {
        parent::init();
        if (!($this->description instanceof DescriptionDirectory)) {
            throw new InvalidConfigException(static::class . '::description must be set as instance of ' . DescriptionDirectory::class);
        }
    }


    public function run(): string
    {
        $count = $this->newsRepository->countByDescription($this->description->id);
        return $this->render('count/description', ['count' => $count]);
    }


}