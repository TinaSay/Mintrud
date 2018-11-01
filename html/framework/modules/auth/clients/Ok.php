<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 11.04.16
 * Time: 19:01
 */

namespace app\modules\auth\clients;

use yii\authclient\OAuth2;

/**
 * Class Ok
 *
 * @package app\modules\auth\clients
 */
class Ok extends OAuth2
{
    /**
     * @var string
     */
    public $authUrl = 'https://connect.ok.ru/oauth/authorize';

    /**
     * @var string
     */
    public $tokenUrl = 'https://api.ok.ru/oauth/token.do';

    /**
     * @var string
     */
    public $apiBaseUrl = 'http://api.odnoklassniki.ru/fb.do?';

    /**
     * @var null|string
     */
    public $applicationKey = null;

    public function init()
    {
        parent::init();
        $this->setViewOptions(
            [
                'popupWidth' => 580,
                'popupHeight' => 345,
            ]
        );
    }

    /**
     * @return array
     */
    protected function initUserAttributes()
    {
        $token = $this->accessToken->token;
        $secret = $this->clientSecret;

        $response = $this->api($this->apiBaseUrl, 'GET', [
            'method' => 'users.getCurrentUser',
            'application_key' => $this->applicationKey,
            'sig' => hash('md5',
                'application_key=' . $this->applicationKey . 'method=users.getCurrentUser' . hash('md5',
                    $token . $secret)),
        ]);

        return $response;
    }

    /**
     * @return string
     */
    protected function defaultTitle()
    {
        return 'ОК';
    }

    /**
     * @return string
     */
    protected function defaultName()
    {
        return 'odnoklassniki';
    }
}
