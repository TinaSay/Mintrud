<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 08.07.2017
 * Time: 13:07
 */

declare(strict_types=1);

namespace app\modules\questionnaire\controllers\frontend;


use app\modules\questionnaire\models\Questionnaire;
use app\modules\system\components\frontend\Controller;
use yii\data\ActiveDataProvider;

/**
 * Class QuestionnaireController
 * @package app\modules\questionnaire\controllers\frontend
 */
class QuestionnaireController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex(): string
    {
        $query = Questionnaire::find()->hidden();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }
}