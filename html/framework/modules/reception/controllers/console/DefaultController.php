<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 19.07.17
 * Time: 14:23
 */

namespace app\modules\reception\controllers\console;

use app\modules\reception\models\Appeal;
use app\modules\reception\services\SendAppealService;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * Class DefaultController
 *
 * @package app\modules\reception\controllers\console
 */
class DefaultController extends Controller
{
    /**
     * @var int
     */
    public $interval = 200000;

    public function init()
    {
        parent::init();
        ini_set('max_execution_time', 600);
    }

    /**
     * @return int
     */
    public function actionIndex()
    {
        $service = new SendAppealService();

        $list = Appeal::find()->where([
            'status' => [
                Appeal::STATUS_LOADED,
                Appeal::STATUS_REGISTERED,
                Appeal::STATUS_EXECUTOR_ASSIGNED,
            ],
        ])->limit(100)
            ->orderBy(['updated_at' => SORT_ASC])
            ->all();

        foreach ($list as $model) {
            /** @var Appeal $model */
            $service->getStatus($model);
            usleep($this->interval);
        }

        return ExitCode::OK;
    }
}