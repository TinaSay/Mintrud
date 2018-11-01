<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 15:36
 */

namespace app\modules\favorite\controllers\frontend;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\services\frontend\AddService;
use app\modules\favorite\services\frontend\DeleteService;
use app\modules\favorite\services\frontend\ListService;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;
use yii\di\Instance;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\Request;
use yii\web\Response;

/**
 * Class DefaultController
 *
 * @package app\modules\favorite\controllers\frontend
 */
class DefaultController extends Controller
{
    /**
     * @var string
     */
    public $layout = '//cabinetInner';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    /**
     * DefaultController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param Request $request
     * @param array $config
     */
    public function __construct($id, Module $module, Request $request, array $config = [])
    {
        $this->request = $request;
        $this->response = Instance::ensure('response');

        parent::__construct($id, $module, $config);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $list = Yii::createObject(ListService::class)->execute();

        return $this->render('index', ['list' => $list]);
    }

    /**
     * @param string $url
     */
    public function actionDelete($url)
    {
        Yii::createObject(DeleteService::class, [$url])->execute();
    }

    /**
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function actionPush()
    {
        $this->response->format = $this->response::FORMAT_JSON;
        $form = Yii::createObject(AddForm::class);

        if ($form->load($this->request->post())) {
            $result = Yii::createObject(AddService::class, [$form])->execute();

            if ($result === true) {
                return $this->asJson(['success' => 'OK']);
            }
        }

        throw new BadRequestHttpException();
    }

    public function actionPop()
    {
        $this->response->format = $this->response::FORMAT_JSON;
        $form = Yii::createObject(AddForm::class);

        if ($form->load($this->request->post())) {
            Yii::createObject(DeleteService::class, [$form->getUrl()])->execute();
            return $this->asJson(['success' => 'OK']);
        }

        throw new BadRequestHttpException();
    }
}
