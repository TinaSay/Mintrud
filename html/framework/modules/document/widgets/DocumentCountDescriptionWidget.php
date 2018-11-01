<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 03.08.2017
 * Time: 11:48
 */

declare(strict_types = 1);


namespace app\modules\document\widgets;


use app\modules\document\models\DescriptionDirectory;
use app\modules\document\models\repository\DocumentRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class DocumentCountWidget
 * @package app\modules\document\widgets
 */
class DocumentCountDescriptionWidget extends Widget
{
    /** @var DescriptionDirectory */
    public $description;

    /**
     * @var DocumentRepository
     */
    private $documentRepository;

    /**
     * DocumentCountWidget constructor.
     * @param DocumentRepository $documentRepository
     * @param array $config
     */
    public function __construct(
        DocumentRepository $documentRepository,
        array $config = [])
    {
        parent::__construct($config);
        $this->documentRepository = $documentRepository;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();

        if (!($this->description instanceof DescriptionDirectory)) {
            throw new InvalidConfigException(static::class . '::descriptionDirectory must be set as instance of ' . DescriptionDirectory::class);
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $count = $this->documentRepository->countByDescription($this->description->id);

        return $this->render('count/description-document', ['count' => $count]);
    }
}