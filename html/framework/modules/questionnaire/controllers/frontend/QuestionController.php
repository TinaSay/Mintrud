<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 13:33
 */

declare(strict_types=1);

namespace app\modules\questionnaire\controllers\frontend;

use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\repositories\frontend\QuestionnaireRepository;
use app\modules\questionnaire\models\repositories\QuestionRepository;
use app\modules\questionnaire\models\Result;
use app\modules\questionnaire\services\ResultService;
use app\modules\questionnaire\services\ValidateException;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;
use yii\caching\TagDependency;
use yii\captcha\CaptchaAction;
use yii\web\NotFoundHttpException;

/**
 * Class QuestionController
 *
 * @package app\modules\questionnaire\controllers\frontend
 */
class QuestionController extends Controller
{
    /**
     * @var QuestionRepository
     */
    private $questionRep;
    /**
     * @var ResultService
     */
    private $resultService;
    /**
     * @var QuestionnaireRepository
     */
    private $questionnaireRepository;

    /**
     * QuestionController constructor.
     * @param string $id
     * @param Module $module
     * @param QuestionRepository $questionRep
     * @param QuestionnaireRepository $questionnaireRepository
     * @param ResultService $resultService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        QuestionRepository $questionRep,
        QuestionnaireRepository $questionnaireRepository,
        ResultService $resultService,
        array $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->questionRep = $questionRep;
        $this->resultService = $resultService;
        $this->questionnaireRepository = $questionnaireRepository;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_DEV ? 'test' : null,
            ],
        ];
    }


    /**
     * @param $id
     *
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionView($id)
    {
        $questionnaire = $this->questionnaireRepository->findOne((int)$id);
        $this->questionnaireRepository->notFoundException($questionnaire);

        $questions = $this->questionRep->findByQuestionnaire((int)$id);

        if (!$this->resultService->allowAnswer($questionnaire)) {
            return $this->render('success', ['model' => $questionnaire]);
        }

        if (Yii::$app->request->isPost) {
            try {
                $this->resultService->create($questionnaire->id);
                return $this->render('success', ['model' => $questionnaire]);
            } catch (ValidateException $exception) {
                foreach ($exception->getErrors() as $error) {
                    foreach ($error as $message) {
                        Yii::$app->session->addFlash('error', $message);
                    }
                }
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render(
            'view',
            [
                'questions' => $questions,
                'questionnaire' => $questionnaire,
            ]
        );
    }

    /**
     * @param string $name
     *
     * @return string|\yii\web\Response
     * @throws \Exception
     */
    public function actionViewByAlias($name)
    {
        $questionnaire = Questionnaire::findOne([
            'name' => $name,
        ]);
        if (!$questionnaire) {
            throw new NotFoundHttpException('Page does not exists');
        }
        $questions = $this->questionRep->findByQuestionnaire($questionnaire->id);

        if (Yii::$app->request->isPost) {
            try {
                $this->resultService->create($questionnaire->id);
                return $this->redirect(['/nsok/' . $name . '/result', 'status' => 'ok']);
            } catch (ValidateException $exception) {
                foreach ($exception->getErrors() as $error) {
                    foreach ($error as $message) {
                        Yii::$app->session->addFlash('error', $message);
                    }
                }
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render(
            'view',
            [
                'questions' => $questions,
                'questionnaire' => $questionnaire,
            ]
        );
    }

    /**
     * @param $name
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionResult($name)
    {
        $questionnaire = Questionnaire::findOne([
            'name' => $name,
        ]);
        if (!$questionnaire) {
            throw new NotFoundHttpException('Page does not exists');
        }

        $key = [
            __CLASS__,
            __METHOD__,
        ];

        $dependency = new TagDependency([
            'tags' => [
                Result::class
            ],
        ]);

        if (!($questions = Yii::$app->cache->get($key))) {
            $answers = $this->questionRep->findByQuestionnaireWithAnswersStat($questionnaire->id);

            $questions = [];
            foreach ($answers as $answer) {
                if (!isset($questions[$answer['id']])) {
                    $questions[$answer['id']] = [
                        'id' => $answer['id'],
                        'title' => $answer['title'],
                        'results' => $answer['results'],
                        'answers' => [
                            [
                                'id' => $answer['answer_id'],
                                'title' => $answer['answer_title'],
                                'results' => $answer['results'],
                            ],
                        ],
                    ];
                } else {
                    $questions[$answer['id']]['results'] += $answer['results'];
                    array_push($questions[$answer['id']]['answers'], [
                        'id' => $answer['answer_id'],
                        'title' => $answer['answer_title'],
                        'results' => $answer['results'],
                    ]);
                }

            }
            Yii::$app->cache->set($key, $questions, null, $dependency);
        }


        return $this->render(
            'result',
            [
                'questions' => $questions,
                'questionnaire' => $questionnaire,
            ]
        );
    }
}