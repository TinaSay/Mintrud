<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 11:09
 */

namespace app\modules\cabinet\controllers\frontend;

use app\modules\cabinet\components\UserFactory;
use app\modules\cabinet\form\BlindConfigureForm;
use app\modules\cabinet\models\Client;
use app\modules\system\components\frontend\Controller;
use Yii;
use yii\base\Module;

/**
 * Class BlindConfigureController
 *
 * @package app\modules\cabinet\controllers\frontend
 */
class BlindConfigureController extends Controller
{
    /**
     * @var UserFactory
     */
    protected $factory;

    /**
     * BlindConfigureController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param UserFactory $factory
     * @param array $config
     */
    public function __construct($id, Module $module, UserFactory $factory, array $config = [])
    {
        $this->factory = $factory;
        parent::__construct($id, $module, $config);
    }

    /**
     * @param string $value
     */
    public function actionFontSize(string $value)
    {
        $model = $this->getUser();

        /** @var BlindConfigureForm $blindConfigureForm */
        $blindConfigureForm = $this->factory->form('BlindConfigure', [$model]);

        $blindConfigureForm->setScenario($blindConfigureForm::SCENARIO_FONT_SIZE);
        $blindConfigureForm->setFontSize($value);

        $blindConfigureService = $this->factory->service('BlindConfigure', [$blindConfigureForm, $model]);

        $blindConfigureService->execute();
    }

    /**
     * @param string $value
     */
    public function actionColorSchema(string $value)
    {
        $model = $this->getUser();

        /** @var BlindConfigureForm $blindConfigureForm */
        $blindConfigureForm = $this->factory->form('BlindConfigure', [$model]);

        $blindConfigureForm->setScenario($blindConfigureForm::SCENARIO_COLOR_SCHEMA);
        $blindConfigureForm->setColorSchema($value);

        $blindConfigureService = $this->factory->service('BlindConfigure', [$blindConfigureForm, $model]);

        $blindConfigureService->execute();
    }

    /**
     * @return Client|null
     */
    protected function getUser()
    {
        $id = Yii::$app->getUser()->getIdentity()->getId();

        return Client::findOne($id);
    }
}
