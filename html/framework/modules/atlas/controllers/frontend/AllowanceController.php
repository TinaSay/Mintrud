<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 18.06.2017
 * Time: 12:49
 */
declare(strict_types=1);


namespace app\modules\atlas\controllers\frontend;

use app\modules\atlas\models\AtlasAllowance;
use app\modules\atlas\models\AtlasDirectory;
use app\modules\atlas\models\AtlasDirectoryAllowance;
use app\modules\atlas\models\AtlasDirectorySubjectRf;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\caching\TagDependency;
use yii\web\Response;

/**
 * Class AllowanceController
 *
 * @package app\modules\atlas\controllers\frontend
 */
class AllowanceController extends Controller
{

    /**
     * @return string
     */
    public function actionIndex()
    {
        $rates = AtlasDirectoryAllowance::getTree(AtlasDirectory::HIDDEN_NO);


        return $this->render('index', [
            'rates' => $rates,
        ]);
    }

    /**
     * @return array
     */
    public function actionGetLayer()
    {

        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['success' => true, 'list' => []];
    }


    /**
     * @param $reg int
     * @param $rate string
     *
     * @return string
     */
    public function actionGetRegionData($reg, $rate)
    {

        $key = [
            __CLASS__,
            __METHOD__,
            $reg,
            $rate,
        ];

        $dependency = new TagDependency([
            'tags' => [
                AtlasAllowance::class,
                AtlasDirectoryAllowance::class,
                AtlasDirectorySubjectRf::class,
            ],
        ]);

        if (!($data = Yii::$app->cache->get($key))) {
            $region = AtlasDirectorySubjectRf::findOne([
                'code' => $reg,
                'type' => AtlasDirectorySubjectRf::getType(),
            ]);

            $model = AtlasAllowance::find()
                ->joinWith('directoryAllowance', false, 'INNER JOIN')
                ->onCondition(['[[allowance]].[[code]]' => $rate])
                ->one();

            $data = [
                'model' => $model,
                'region' => $region,
            ];
            Yii::$app->cache->set($key, $data, null, $dependency);
        }

        return $this->renderAjax('_stat', $data + [
                'reg_id' => $reg,
            ]);
    }

}