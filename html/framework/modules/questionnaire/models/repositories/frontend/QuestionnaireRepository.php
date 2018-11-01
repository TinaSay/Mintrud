<?php
/**
 * Created by PhpStorm.
 * User: cheremhovo
 * Date: 19.10.17
 * Time: 14:48
 */

namespace app\modules\questionnaire\models\repositories\frontend;


use app\modules\questionnaire\models\Questionnaire;
use yii\web\NotFoundHttpException;

/**
 * Class QuestionnaireRepository
 * @package app\modules\questionnaire\models\repositories\frontend
 */
class QuestionnaireRepository
{

    /**
     * @param int $id
     * @param array $with
     * @return Questionnaire|null
     */
    public function findOne(int $id, array $with = []): ?Questionnaire
    {
        $model = Questionnaire::find()
            ->with($with)
            ->id($id)
            ->hidden()
            ->limit(1)
            ->one();

        return $model;
    }

    /**
     * @param Questionnaire|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundException(Questionnaire $model = null)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }
}