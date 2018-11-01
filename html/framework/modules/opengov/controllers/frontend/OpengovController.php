<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 12:44
 */

// declare(strict_types=1);


namespace app\modules\opengov\controllers\frontend;


use app\modules\opengov\models\repository\OpengovRepository;
use app\modules\system\components\frontend\Controller;
use yii\base\Module;

class OpengovController extends Controller
{
    /**
     * @var OpengovRepository
     */
    private $opengovRep;

    public function __construct(
        $id,
        Module $module,
        OpengovRepository $opengovRep,
        array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->opengovRep = $opengovRep;
    }


    public function actionView($id)
    {
        $model = $this->opengovRep->findOne($id);
        return $this->render('view', ['model' => $model]);
    }
}