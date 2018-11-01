<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 20.10.17
 * Time: 12:19
 */

declare(strict_types=1);

namespace app\modules\questionnaire\widgets;


use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\repositories\frontend\ResultAnswerRepository;
use app\modules\questionnaire\models\repositories\frontend\ResultRepository;
use yii\base\InvalidConfigException;
use yii\base\Widget;

/**
 * Class CartWidget
 * @package app\modules\questionnaire\widgets
 */
class CartWidget extends Widget
{
    /**
     * @var ResultRepository
     */
    private $resultRepository;
    /**
     * @var ResultAnswerRepository
     */
    private $resultAnswerRepository;

    /**
     * CartWidget constructor.
     * @param ResultRepository $resultRepository
     * @param ResultAnswerRepository $resultAnswerRepository
     * @param array $config
     */
    public function __construct(
        ResultRepository $resultRepository,
        ResultAnswerRepository $resultAnswerRepository,
        array $config = []
    )
    {
        parent::__construct($config);
        $this->resultRepository = $resultRepository;
        $this->resultAnswerRepository = $resultAnswerRepository;
    }


    /** @var Questionnaire */
    public $model;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        if (!$this->model instanceof Questionnaire) {
            throw new InvalidConfigException(static::class . ':: model must be set');
        }
    }

    /**
     * @return string
     */
    public function run(): string
    {
        if (!$this->model->isBarCart()) {
            return '';
        }

        return $this->render('cart/bar-cart', ['model' => $this->model]);
    }


    /**
     * @return int
     */
    public function countResult(): int
    {
        return $this->resultRepository->countByQuestionnaire($this->model->id);
    }


    /**
     * @param int $questionId
     * @param int $answerId
     * @return int
     */
    public function countAnswer(int $questionId, int $answerId): int
    {
        return $this->resultAnswerRepository->countByQuestionAndAnswer($questionId, $answerId);
    }

    /**
     * @param int $questionId
     * @param int $answerId
     * @return float
     */
    public function getPercent(int $questionId, int $answerId): float
    {
        $count = $this->countAnswer($questionId, $answerId);
        $summ = $this->countResult();
        return $count * 100 / $summ;
    }
}