<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.07.2017
 * Time: 13:51
 */

declare(strict_types = 1);

namespace app\modules\document\widgets;


use app\modules\document\models\repository\DocumentRepository;
use app\modules\document\models\WidgetOnMain;
use yii\base\InvalidConfigException;
use yii\base\Widget;


/**
 * Class DocumentOnMainListWidget
 * @package app\modules\document\widgets
 */
class DocumentOnMainListWidget extends Widget
{
    /** @var WidgetOnMain */
    public $tab;

    /** @var bool */
    public $active;
    /**
     * @var DocumentRepository
     */
    private $documentRepository;


    /**
     * DocumentOnMainListWidget constructor.
     * @param DocumentRepository $documentRepository
     * @param array $config
     */
    public function __construct(
        DocumentRepository $documentRepository,
        array $config = []
    )
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

        if (!($this->tab instanceof WidgetOnMain)) {
            throw new InvalidConfigException(static::class . '::tab must be set as instance of ' . WidgetOnMain::class);
        }

        if (!is_bool($this->active)) {
            throw new InvalidConfigException(static::class . '::active must be set as boolean type');
        }
    }


    /**
     * @return string
     */
    public function run(): string
    {
        $models = $this->documentRepository->listOnMain($this->tab, 6);

        return $this->render('main/list', ['models' => $models]);
    }


    /**
     * @return int
     */
    public function getTabId(): int
    {
        return $this->tab->id;
    }

    /**
     * @return null|string
     */
    public function getClassActive(): ?string
    {
        if ($this->active) {
            return 'fade active in';
        } else {
            return null;
        }
    }
}