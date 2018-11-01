<?php

namespace app\components\language;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\HeaderCollection;
use yii\web\Request;

/**
 * Class LanguageRequest
 *
 * @package app\components\language
 */
class LanguageRequest extends Request
{
    /**
     * List of allowed request methods for this application
     *
     * @var array
     */
    public static $allowedMethods = [
        // http methods
        'GET',
        'POST',
        // rest methods
        'PUT',
        'DELETE',
        // 'PATCH',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if (!in_array($this->getMethod(), self::$allowedMethods)) {
            Yii::$app->response->setStatusCode(405);
            Yii::$app->response->content = 'Method Not Allowed';
            Yii::$app->response->send();
            Yii::$app->end();
        }
    }

    /**
     * Filters headers according to the [[trustedHosts]].
     *
     * @param HeaderCollection $headerCollection
     *
     * @since 2.0.13
     */
    protected function filterHeaders(HeaderCollection $headerCollection)
    {
        // do not trust any of the [[secureHeaders]] by default
        $trustedHeaders = [];

        // check if the client is a trusted host
        if (!empty($this->trustedHosts)) {
            // insecure hook for transparent nginx proxy
            if (isset($this->trustedHosts[$headerCollection->get('host')])) {
                $trustedHeaders = $this->trustedHosts[$headerCollection->get('host')];
            } else {
                $validator = $this->getIpValidator();
                $ip = $this->getRemoteIP();
                foreach ($this->trustedHosts as $cidr => $headers) {
                    if (!is_array($headers)) {
                        $cidr = $headers;
                        $headers = $this->secureHeaders;
                    }
                    $validator->setRanges($cidr);
                    if ($validator->validate($ip)) {
                        $trustedHeaders = $headers;
                        break;
                    }
                }
            }
        }

        // filter all secure headers unless they are trusted
        foreach ($this->secureHeaders as $secureHeader) {
            if (!in_array($secureHeader, $trustedHeaders)) {
                $headerCollection->remove($secureHeader);
            }
        }
    }

    /**
     * @return bool|string
     */
    protected function resolveRequestUri()
    {
        $pattern = [];
        $resolveRequestUri = parent::resolveRequestUri();

        if (Yii::$app->getUrlManager()->enablePrettyUrl === true && Yii::$app->getUrlManager()->suffix) {
            $pattern[] = '/' . preg_replace('/\//', '\/', Yii::$app->getUrlManager()->suffix) . '$/';
        }

        if (Yii::$app->getUrlManager()->showScriptName === true) {
            $pattern[] = '/' . preg_replace('/\//', '\/', $this->getScriptUrl()) . '/';
        }

        $requestUri = preg_replace($pattern, '', $resolveRequestUri);

        list($language,) = explode('/', trim($requestUri, '/'));

        if (Yii::$app->get('language')->has($language)) {
            Yii::$app->language = $language;
        } else {
            Yii::$app->language = $this->getPreferredLanguage(ArrayHelper::getColumn(Yii::$app->get('language')->getList(),
                'iso'));
        }

        return $resolveRequestUri;
    }
}
