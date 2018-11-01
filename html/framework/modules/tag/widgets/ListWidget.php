<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 06.09.2017
 * Time: 14:40
 */

namespace app\modules\tag\widgets;


use app\modules\tag\models\repositories\TagRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class ListWidget
 * @package app\modules\tag\widgets
 */
class ListWidget extends Widget
{
    /**
     * @var TagRepository
     */
    private $tagRepository;

    /**
     * ListWidget constructor.
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
     * @var
     */
    public $model;

    /**
     * @var string
     */
    public $view = 'list';

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (is_null($this->model)) {
            throw new InvalidConfigException(static::class . '::model must be set');
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        $models = $this->tagRepository->findByClassGroupByIdWithLimitOrder($this->model, 15);
        return $this->render('list', ['models' => $models]);
    }
}