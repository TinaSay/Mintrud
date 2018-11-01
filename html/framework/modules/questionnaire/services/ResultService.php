<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 19.10.17
 * Time: 14:42
 */

namespace app\modules\questionnaire\services;

use app\modules\questionnaire\models\Questionnaire;
use app\modules\questionnaire\models\repositories\frontend\QuestionnaireRepository;
use app\modules\questionnaire\models\repositories\frontend\ResultRepository;
use app\modules\questionnaire\models\result\Result;
use Yii;
use yii\web\Request;

class ResultService
{
    /**
     * @var QuestionnaireRepository
     */
    private $questionnaireRepository;
    /**
     * @var Request
     */
    private $request;
    /**
     * @var ResultRepository
     */
    private $resultRepository;

    /**
     * ResultService constructor.
     * @param QuestionnaireRepository $questionnaireRepository
     * @param Request $request
     * @param ResultRepository $resultRepository
     */
    public function __construct(
        QuestionnaireRepository $questionnaireRepository,
        Request $request,
        ResultRepository $resultRepository
    )
    {

        $this->questionnaireRepository = $questionnaireRepository;
        $this->request = $request;
        $this->resultRepository = $resultRepository;
    }


    public function create(int $questionnaireId)
    {
        $questionnaire = $this->questionnaireRepository->findOne($questionnaireId);
        $this->questionnaireRepository->notFoundException($questionnaire);

        if (!$this->allowAnswer($questionnaire)) {
            $this->domainsException('Ваш ответ успешно отправлен, спасибо за Ваше мнение!');
        };

        $result = new Result();
        $result->questionnaire_id = $questionnaire->id;
        $result->queryParams = $this->request->post();
        $result->captcha = $this->request->post($result::CAPTCHA_NAME);
        $beginTransaction = Yii::$app->db->beginTransaction();
        try {
            if ($result->validate() && $result->save(false)) {
                $beginTransaction->commit();
            } else {
                if ($result->hasErrors()) {
                    throw new ValidateException($result->getErrors());
                } else {
                    $this->domainsException();
                }
            }
        } catch (\Exception $e) {
            $beginTransaction->rollBack();
            throw $e;
        }
    }


    /**
     * @param Questionnaire $questionnaire
     * @return bool
     */
    public function allowAnswer(Questionnaire $questionnaire): bool
    {
        if ($questionnaire->isRestrictionByIp()) {
            return !$this->resultRepository->existsByQuestionnaireAndIp($questionnaire->id, ip2long($this->request->getUserIP()));
        } else {
            return true;
        }
    }

    /**
     * @param string $message
     */
    public function domainsException($message = 'Ошибка')
    {
        throw new \DomainException($message);
    }
}