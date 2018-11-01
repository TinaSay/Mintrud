<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 07.08.17
 * Time: 14:41
 */

namespace app\modules\testing\controllers\console;

use app\modules\testing\models\Testing;
use app\modules\testing\services\ExportService;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;

class ExportController extends Controller
{
    /**
     * @var int
     */
    public $id;

    /**
     *
     */
    public function init()
    {
        ignore_user_abort(true);
        error_reporting(E_ALL);
        ini_set('display_errors', 'On');
        ini_set('memory_limit', '1024M');
        ini_set('max_execution_time', 600);
        set_time_limit(600);
        parent::init();
    }

    /**
     * @param string $actionID
     *
     * @return array
     */
    public function options($actionID)
    {
        return ArrayHelper::merge(['id'], parent::options($actionID));
    }

    /**
     * @return int
     */
    public function actionIndex()
    {
        $path = Yii::getAlias(ExportService::EXPORT_PATH . '/export.id');
        if ($this->id <= 0 && file_exists($path)) {
            $this->id = (int)file_get_contents($path);
            @unlink($path);
        }
        if ($this->id > 0) {
            $model = Testing::findOne($this->id);

            if (!$model) {
                print 'Testing with id ' . $this->id . ' not found' . PHP_EOL;

                return static::EXIT_CODE_ERROR;
            }

            $service = new ExportService(
                Yii::getAlias(ExportService::EXPORT_TEMPLATE_PATH),
                'Excel2007'
            );

            $service->exportExcel($model);

        }

        return static::EXIT_CODE_NORMAL;
    }

}