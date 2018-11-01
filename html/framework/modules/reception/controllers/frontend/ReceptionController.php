<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 14.07.17
 * Time: 11:13
 */

namespace app\modules\reception\controllers\frontend;

use app\modules\reception\models\Appeal;
use Yii;
use yii\web\Controller;

/**
 * Class ReceptionController
 *
 * @package app\modules\reception\controllers\frontend
 */
class ReceptionController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//cabinetInner';

    /**
     * @return string
     */
    public function actionIndex()
    {
        $list = Appeal::find()->where([
            'client_id' => Yii::$app->getUser()->getId(),
            'status' => [
                Appeal::STATUS_LOADED,
                Appeal::STATUS_REGISTERED,
                Appeal::STATUS_EXECUTOR_ASSIGNED,
                Appeal::STATUS_REJECTED,
                Appeal::STATUS_EXECUTOR_ANSWERED,
            ],
        ])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();

        return $this->render('index', [
            'list' => $list,
        ]);
    }
}
