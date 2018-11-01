<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 9:37
 */

// declare(strict_types=1);


namespace app\modules\programm\controllers\frontend;


use app\modules\programm\models\repository\ProgrammRepository;
use app\modules\system\components\frontend\Controller;
use yii\base\Module;

/**
 * Class ProgrammController
 * @package app\modules\programm\controllers\frontend
 */
class ProgrammController extends Controller
{
    /**
     * @var ProgrammRepository
     */
    private $programmRep;

    /**
     * ProgrammController constructor.
     * @param string $id
     * @param Module $module
     * @param ProgrammRepository $programmRep
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        ProgrammRepository $programmRep,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->programmRep = $programmRep;
    }


    /**
     * @param $id
     * @return string
     */
    public function actionView($id): string
    {
        $model = $this->programmRep->findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    public function actionViewByUrl($url)
    {
        $model = $this->programmRep->findOneByUrl($url);
        return $this->render('view-by-url', ['model' => $model]);
    }
}