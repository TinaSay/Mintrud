<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.06.2017
 * Time: 17:31
 */

declare(strict_types=1);

namespace app\modules\news\widgets;

use app\modules\directory\models\Directory;
use app\modules\directory\models\repository\DirectoryRepository;
use app\modules\news\models\News;
use app\modules\news\models\repository\NewsRepository;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\caching\TagDependency;
use yii\helpers\ArrayHelper;

/**
 * Class TabOnMainWidget
 *
 * @package app\modules\news\widgets
 */
class TabOnMainWidget extends Widget
{
    /**
     * @var int|string
     */
    public $numberTab;

    /**
     * @var integer
     */
    public $directory_id;

    /**
     * @var bool
     */
    public $active = false;

    /**
     * @var DirectoryRepository
     */
    private $directoryRepository;

    /**
     * @var NewsRepository
     */
    private $newsRepository;

    public function __construct(
        DirectoryRepository $directoryRepository,
        NewsRepository $newsRepository,
        array $config = []
    ) {
        parent::__construct($config);
        $this->directoryRepository = $directoryRepository;
        $this->newsRepository = $newsRepository;
    }

    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        if (is_null($this->numberTab)) {
            throw new InvalidConfigException(static::class . '::numberTab must be set');
        }
        if (!is_bool($this->active)) {
            throw new InvalidConfigException(static::class . '::active must be set as bool');
        }
        parent::init();
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $key = [
            __CLASS__,
            __METHOD__,
            __LINE__,
            $this->directory_id,
            Yii::$app->language,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Directory::class,
                News::class,
            ],
        ]);

        if (!($models = Yii::$app->cache->get($key))) {
            if (is_integer($this->directory_id)) {
                $models = $this->findWithDirectory();
            } else {
                $models = $this->find();
            }
            Yii::$app->cache->set($key, $models, null, $dependency);
        }

        return $this->render('main/tab', ['models' => $models]);
    }

    /**
     * @return string
     */
    public function getNumberTab(): string
    {
        return (string)$this->numberTab;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @return array
     */
    public function find(): array
    {
        return News::find()
            ->innerJoinWith(['directory'])
            ->language()
            ->directoryHidden()
            ->hidden()
            ->limit(6)
            ->orderByDate()
            ->all();
    }

    /**
     * @return array
     */
    public function findWithDirectory(): array
    {
        $directoryIds = ArrayHelper::getColumn($this->directoryRepository->findChildren($this->directory_id), 'id');

        $news = $this->newsRepository->findByDirectoriesWithLimit($directoryIds, 9);

        return $news;
    }
}
