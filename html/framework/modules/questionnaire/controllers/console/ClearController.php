<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 21.11.17
 * Time: 16:50
 */

namespace app\modules\questionnaire\controllers\console;


use app\modules\questionnaire\models\repositories\frontend\ResultRepository;
use app\modules\questionnaire\models\repositories\ResultAnswerRepository;
use app\modules\questionnaire\models\repositories\ResultAnswerTextRepository;
use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\models\ResultAnswer;
use yii\base\Module;
use yii\console\Controller;

class ClearController extends Controller
{
    const ID = 3;

    const ANSWER_1_ID = 154;
    const COUNT_1_ANSWER = 16;

    const ANSWER_2_ID = 155;
    const COUNT_2_ANSWER = 12;

    const ANSWER_3_ID = 156;
    const COUNT_3_ANSWER = 11;

    const QUESTION_ID = 32;

    const IP = '83.69.213.187';

    public $ips = [
        '176.37.228.44',
    ];
    /**
     * @var ResultRepository
     */
    private $resultRepository;
    /**
     * @var ResultAnswerRepository
     */
    private $resultAnswerRepository;
    /**
     * @var ResultAnswerTextRepository
     */
    private $answerTextRepository;

    /**
     * ClearController constructor.
     * @param string $id
     * @param Module $module
     * @param ResultRepository $resultRepository
     * @param ResultAnswerRepository $resultAnswerRepository
     * @param ResultAnswerTextRepository $answerTextRepository
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        ResultRepository $resultRepository,
        ResultAnswerRepository $resultAnswerRepository,
        ResultAnswerTextRepository $answerTextRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->resultRepository = $resultRepository;
        $this->resultAnswerRepository = $resultAnswerRepository;
        $this->answerTextRepository = $answerTextRepository;
    }


    public function actionByIp()
    {
        for ($i = 1; $i <= 255; $i++) {
            $this->ips[] = '192.168.4.' . $i;
        }

        foreach ($this->ips as $ip) {
            echo $ip . ' - ' . ip2long($ip) . PHP_EOL;
            $results = Result::find()->questionnaire(static::ID)->ip((int)ip2long($ip))->all();
            foreach ($results as $result) {
                foreach ($result->resultAnswers as $resultAnswer) {
                    $this->resultAnswerRepository->delete($resultAnswer);
                }

                foreach ($result->resultAnswerTexts as $resultAnswerText) {
                    $this->answerTextRepository->delete($resultAnswerText);
                }
                echo long2ip($result->ip) . ' deleting ' . PHP_EOL;
                $this->resultRepository->delete($result);
            }
        }
    }

    public function actionByDouble()
    {
        $ids = [
            '1366',
            '1355',
            '1393',
            '1357',
            '1358',
            '1362',
            '1102',
        ];
        $results = Result::find()->questionnaire(static::ID)->inId($ids)->all();

        foreach ($results as $result) {
            foreach ($result->resultAnswers as $resultAnswer) {
                $this->resultAnswerRepository->delete($resultAnswer);
            }

            foreach ($result->resultAnswerTexts as $resultAnswerText) {
                $this->answerTextRepository->delete($resultAnswerText);
            }
            echo long2ip($result->ip) . ' deleting ' . PHP_EOL;
            $this->resultRepository->delete($result);
        }
    }

    public function actionFill()
    {
        echo 'START' . PHP_EOL;
        for ($i = 1; $i <= static::COUNT_1_ANSWER; $i++) {
            $result = Result::create(static::ID, ip2long(static::IP));
            $result->detachBehavior('IpBehavior');
            $this->resultRepository->save($result, false);
            $answer = ResultAnswer::create($result->id, static::QUESTION_ID, static::ANSWER_1_ID);
            $this->resultAnswerRepository->save($answer);
        }

        for ($i = 1; $i <= static::COUNT_2_ANSWER; $i++) {
            $result = Result::create(static::ID, ip2long(static::IP));
            $result->detachBehavior('IpBehavior');
            $this->resultRepository->save($result, false);
            $answer = ResultAnswer::create($result->id, static::QUESTION_ID, static::ANSWER_2_ID);
            $this->resultAnswerRepository->save($answer);
        }

        for ($i = 1; $i <= static::COUNT_3_ANSWER; $i++) {
            $result = Result::create(static::ID, ip2long(static::IP));
            $result->detachBehavior('IpBehavior');
            $this->resultRepository->save($result, false);
            $answer = ResultAnswer::create($result->id, static::QUESTION_ID, static::ANSWER_3_ID);
            $this->resultAnswerRepository->save($answer);
        }
        echo 'END' . PHP_EOL;
    }
}