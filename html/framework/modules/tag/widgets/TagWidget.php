<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 30.09.2017
 * Time: 17:30
 */

// declare(strict_types=1);


namespace app\modules\tag\widgets;


use app\modules\tag\interfaces\TagInterface;
use app\modules\tag\models\repositories\TagRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;

/**
 * Class TagWidget
 * @package app\modules\tag\widgets
 */
class TagWidget extends Widget
{
    /** @var ActiveRecord */
    public $instance;
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * TagWidget constructor.
     * @param TagRepository $tagRepository
     * @param array $config
     */
    public function __construct(
        TagRepository $tagRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->tagRepository = $tagRepository;
    }


    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!($this->instance instanceof TagInterface)) {
            throw new InvalidConfigException(static::className() . '::instance must be set');
        }
        if ($this->instance->isNewRecord) {
            throw new InvalidConfigException(static::className() . '::instance');
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        $models = $this->tagRepository->findByRecordModel($this->instance->id, $this->instance::className());

        return $this->render('tag/list', ['models' => $models]);
    }
}