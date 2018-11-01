<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 15:52
 */

// declare(strict_types=1);


namespace app\modules\govserv\controllers\frontend;


use app\modules\govserv\models\repositories\GovservRepository;
use app\modules\system\components\frontend\Controller;
use yii\base\Module;

/**
 * Class GovservController
 * @package app\modules\govserv\controllers\frontend
 */
class GovservController extends Controller
{
    /**
     * @var GovservRepository
     */
    private $govservRep;

    /**
     * GovservController constructor.
     * @param string $id
     * @param Module $module
     * @param array $config
     * @param GovservRepository $govservRep
     */
    public function __construct(
        $id,
        Module $module,
        array $config = [],
        GovservRepository $govservRep
    )
    {
        parent::__construct($id, $module, $config);
        $this->govservRep = $govservRep;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionView($id): string
    {
        $model = $this->govservRep->findOne($id);

        return $this->render('view', ['model' => $model]);
    }


    /**
     * @param $url
     * @return string
     */
    public function actionViewByUrl($url): string
    {
        $model = $this->govservRep->findOneByUrl($url);
        return $this->render('view-by-url', ['model' => $model]);
    }
}