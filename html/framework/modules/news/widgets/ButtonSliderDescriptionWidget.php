<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 10.10.17
 * Time: 15:42
 */

namespace app\modules\news\widgets;


use app\modules\document\controllers\frontend\DescriptionDirectoryController;
use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DocumentRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

class ButtonSliderDescriptionWidget extends Widget implements DescriptionInterface
{
    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    public $description;

    public function __construct(
        DocumentRepository $documentRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->documentRepository = $documentRepository;
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
        return $this->render('slider/button');
    }

    public function hasDocument(): bool
    {
        $models = $this->documentRepository->findByDescription($this->description->id, DescriptionDirectoryController::LIMIT);
        return !empty($models);
    }

}