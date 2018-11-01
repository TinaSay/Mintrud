<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 25.10.17
 * Time: 16:50
 */

namespace app\modules\questionnaire\controllers\console;


use app\modules\questionnaire\models\repositories\frontend\ResultRepository;
use app\modules\questionnaire\models\Result;
use yii\base\Module;
use yii\console\Controller;

class ResultController extends Controller
{
    /**
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * ResultController constructor.
     * @param string $id
     * @param Module $module
     * @param ResultRepository $resultRepository
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        ResultRepository $resultRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->resultRepository = $resultRepository;
    }


    public function actionDelete()
    {
        ini_set('error_reporting', E_ALL);
        ini_set('display_errors', 1);
        $results = Result::find()->each(300);
        echo 'start' . PHP_EOL;
        //var_dump(count($results));
        foreach ($results as $result) {
            if (empty($result->resultAnswers) && empty($result->resultAnswerTexts)) {
                $id = $result->id;
                $this->resultRepository->delete($result);
                echo 'success: ' . $id . PHP_EOL;
            }
        }
        echo 'end' . PHP_EOL;
    }
}