<?php

namespace app\modules\anticorruption\controllers\frontend;

use app\modules\anticorruption\models\repository\AnticorruptionRepository;
use app\modules\system\components\frontend\Controller;
use yii\base\Module;


/**
 * AnticorruptionController implements the CRUD actions for Anticorruption model.
 */
class AnticorruptionController extends Controller
{
    /**
     * @var AnticorruptionRepository
     */
    private $anticorruptionRepository;

    public function __construct(
        $id,
        Module $module,
        AnticorruptionRepository $anticorruptionRepository,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->anticorruptionRepository = $anticorruptionRepository;
    }


    public function actionView($id)
    {
        $model = $this->anticorruptionRepository->findOne($id);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    public function actionViewByUrl($url)
    {
        $model = $this->anticorruptionRepository->findOneByUrl($url);

        return $this->render('view-by-url', [
            'model' => $model,
        ]);
    }
}
