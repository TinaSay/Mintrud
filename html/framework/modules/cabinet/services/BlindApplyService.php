<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 18.07.17
 * Time: 17:33
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\models\Client;
use DateTime;
use Yii;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\Response;

/**
 * Class BlindApplyService
 *
 * @package app\modules\cabinet\services
 */
class BlindApplyService
{
    /**
     * @var Client
     */
    private $model;

    /**
     * @var Cookie
     */
    private $cookie;

    /**
     * @var Response
     */
    private $response;

    /**
     * BlindApplyService constructor.
     *
     * @param Client $model
     * @param Cookie $cookie
     */
    public function __construct(Client $model, Cookie $cookie)
    {
        $this->model = $model;
        $this->cookie = $cookie;
        $this->response = Instance::ensure('response');
    }

    public function execute()
    {
        $configure = $this->model->blind;

        $fontSize = ArrayHelper::getValue($configure, 'fontSize');
        if (!is_null($fontSize)) {
            $this->apply('blind-fontSize', $fontSize);
        }

        $colorSchema = ArrayHelper::getValue($configure, 'colorSchema');
        if (!is_null($colorSchema)) {
            $this->apply('blind-colorSchema', $colorSchema);
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    protected function apply($name, $value)
    {
        $cookie = clone $this->cookie;
        Yii::configure($cookie, [
            'name' => $name,
            'value' => $value,
            'expire' => (new DateTime())->modify('+30 day')->getTimestamp(),
            'httpOnly' => false,
        ]);
        $this->response->getCookies()->add($cookie);
    }
}
