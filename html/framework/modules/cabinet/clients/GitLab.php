<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 27.10.16
 * Time: 11:51
 */

namespace app\modules\cabinet\clients;

use yii\authclient\OAuth2;

/**
 * Class GitLab
 *
 * @package app\modules\cabinet\clients
 */
class GitLab extends OAuth2
{
    /**
     * @var string
     */
    public $authUrl = 'http://gitlab.dev-vps.ru/oauth/authorize';

    /**
     * @var string
     */
    public $tokenUrl = 'http://gitlab.dev-vps.ru/oauth/token';

    /**
     * @var string
     */
    public $apiBaseUrl = 'http://gitlab.dev-vps.ru/api/v3';

    /**
     * @var string
     */
    public $scope = 'api';

    /**
     * @var array
     */
    public $attributeNames = [
        'name',
        'email',
    ];

    /**
     * @return array
     */
    protected function initUserAttributes()
    {
        return $this->api('user', 'GET', [
            'fields' => implode(',', $this->attributeNames),
        ]);
    }

    /**
     * @return string
     */
    protected function defaultName()
    {
        return 'gitlab';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'GitLab';
    }

    /**
     * @return array
     */
    protected function defaultViewOptions()
    {
        return [
            'popupWidth' => 860,
            'popupHeight' => 480,
        ];
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    protected function composeUrl($url, array $params = [])
    {
        return urldecode(parent::composeUrl($url, $params));
    }
}
