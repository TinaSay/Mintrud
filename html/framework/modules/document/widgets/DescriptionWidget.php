<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 24.08.2017
 * Time: 17:40
 */

namespace app\modules\document\widgets;


use app\modules\document\models\repository\DescriptionDirectoryRepository;
use yii\base\Widget;

/**
 * Class DescriptionWidget
 * @package app\modules\document\widgets
 */
class DescriptionWidget extends Widget
{
    /**
     * @var DescriptionDirectoryRepository
     */
    private $descriptionDirectoryRepository;

    /**
     * DescriptionWidget constructor.
     * @param DescriptionDirectoryRepository $descriptionDirectoryRepository
     * @param array $config
     */
    public function __construct(
        DescriptionDirectoryRepository $descriptionDirectoryRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->descriptionDirectoryRepository = $descriptionDirectoryRepository;
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $models = $this->descriptionDirectoryRepository->find();

        return $this->render('description/list', ['models' => $models]);
    }

}