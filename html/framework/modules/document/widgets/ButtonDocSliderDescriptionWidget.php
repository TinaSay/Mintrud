<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 10.10.17
 * Time: 16:03
 */

namespace app\modules\document\widgets;


use app\modules\document\controllers\frontend\DescriptionDirectoryController;
use app\modules\news\models\repository\NewsRepository;
use yii\base\Widget;

class ButtonDocSliderDescriptionWidget extends Widget implements DescriptionInterface
{
    public $description;
    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        NewsRepository $newsRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->newsRepository = $newsRepository;
    }

    public function run(): string
    {
        return $this->render('slider/button');
    }

    public function hasNews(): bool
    {
        $models = $this->newsRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);
        return !empty($models);
    }
}