<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 19.07.17
 * Time: 18:22
 */

namespace app\modules\auth\controllers\backend;

use app\modules\auth\models\Profile;
use app\modules\auth\servives\ProfileService;
use app\modules\auth\types\ProfileType;
use app\modules\system\components\backend\Controller;
use Yii;
use yii\base\Module;

/**
 * Class ProfileController
 *
 * @package app\modules\auth\controllers\backend
 */
class ProfileController extends Controller
{
    /**
     * @var ProfileService
     */
    private $profileService;

    /**
     * ProfileController constructor.
     * @param string $id
     * @param Module $module
     * @param ProfileService $profileService
     * @param array $config
     */
    public function __construct(
        $id,
        Module $module,
        ProfileService $profileService,
        array $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->profileService = $profileService;
    }


    /**
     * @return string|\yii\web\Response
     */
    public function actionIndex()
    {
        $model = $this->findModel();
        $type = new ProfileType($model);
        $ip = Yii::$app->request->getUserIP();
        if ($type->load(Yii::$app->getRequest()->post()) && $type->validate()) {
            try {
                $this->profileService->updateBindIp($model->id, $type, $ip);
                Yii::$app->session->addFlash('info', 'Сохранено');
                return $this->redirect(['index']);
            } catch (\DomainException $exception) {
                Yii::$app->errorHandler->logException($exception);
                Yii::$app->session->addFlash('error', $exception->getMessage());
            }
        }

        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('index', ['model' => $model, 'type' => $type, 'ip' => $ip]);
    }

    /**
     * @return Profile
     */
    protected function findModel()
    {
        return Profile::findOne(Yii::$app->getUser()->getIdentity()->getId());
    }
}
