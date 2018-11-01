<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 17.11.17
 * Time: 10:44
 */

namespace app\modules\document\commands;

use app\modules\document\interfaces\DownloadServiceInterface;
use app\modules\document\models\Document;
use yii\base\Module;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;

/**
 * Class DocumentArchiveController
 *
 * @package app\modules\document\commands
 */
class DocumentArchiveController extends Controller
{
    /**
     * @var DownloadServiceInterface
     */
    protected $service;

    /**
     * DocumentArchiveController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param DownloadServiceInterface $service
     * @param array $config
     */
    public function __construct($id, Module $module, DownloadServiceInterface $service, array $config = [])
    {
        $this->service = $service;
        parent::__construct($id, $module, $config);
        ini_set('memory_limit', '256M');
    }

    /**
     * @return int
     */
    public function actionRun()
    {
        $batch = Document::find()->batch(100);

        $progress = 0;
        $total = Document::find()->count();
        Console::startProgress(0, $total);

        foreach ($batch as $models) {
            foreach ($models as $model) {
                $this->service->create($model->id);

                Console::updateProgress(++$progress, $total);
            }
        }

        Console::endProgress();

        return ExitCode::OK;
    }
}
