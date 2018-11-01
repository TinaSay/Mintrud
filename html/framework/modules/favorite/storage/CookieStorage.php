<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 12:25
 */

namespace app\modules\favorite\storage;

use app\modules\favorite\forms\frontend\AddForm;
use app\modules\favorite\source\SourceInterface;
use DateTime;
use Yii;
use yii\di\Instance;
use yii\helpers\Json;
use yii\web\Cookie;
use yii\web\Request;
use yii\web\Response;

/**
 * Class CookieStorage
 *
 * @package app\modules\favorite\storage
 */
class CookieStorage implements StorageInterface
{
    /**
     * @var string
     */
    public $key = '__favorite';

    /**
     * @var string
     */
    public $cookie = Cookie::class;

    /**
     * @var int expire days
     */
    public $expire = 30;

    /**
     * @var Request
     */
    private $request;

    /**
     * @var Response
     */
    private $response;

    public function __construct()
    {
        $this->request = Instance::ensure('request');
        $this->response = Instance::ensure('response');
    }

    /**
     * @param AddForm $form
     */
    public function push(AddForm $form)
    {
        $cookies = $this->request->getCookies();

        if ($cookies->has($this->key)) {
            $cookie = $cookies->get($this->key);
        } else {
            $cookie = Yii::createObject([
                'class' => $this->cookie,
                'name' => $this->key,
                'expire' => (new DateTime())->modify('+' . $this->expire . ' day')->getTimestamp(),
            ]);
        }

        $value = Json::decode($cookie->value);

        $value[] = [
            'title' => $form->getTitle(),
            'url' => $form->getUrl(),
        ];

        $cookie->value = Json::encode($value);

        $this->response->getCookies()->add($cookie);
    }

    /**
     * @return array|mixed
     */
    public function pull()
    {
        $result = [];
        $cookies = $this->request->getCookies();

        if ($cookies->has($this->key)) {
            $value = $cookies->getValue($this->key);
            $result = Json::decode($value);
        }

        return $result;
    }

    /**
     * @param SourceInterface $source
     *
     * @return bool
     */
    public function exist(SourceInterface $source)
    {
        $result = [];
        $cookies = $this->request->getCookies();

        if ($cookies->has($this->key)) {
            $value = $cookies->getValue($this->key);
            $result = Json::decode($value);
        }

        return array_search($source->getUrl(), array_column($result, 'url')) !== false;
    }

    /**
     * @param string $url
     */
    public function delete($url)
    {
        $cookies = $this->request->getCookies();

        if ($cookies->has($this->key)) {
            $cookie = $cookies->get($this->key);

            $value = Json::decode($cookie->value);
            $value = array_filter($value, function ($row) use ($url) {
                return !in_array($url, $row);
            });
            $cookie->value = Json::encode($value);

            $this->response->getCookies()->add($cookie);
        }
    }
}
