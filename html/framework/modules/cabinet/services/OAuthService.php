<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 06.07.17
 * Time: 10:17
 */

namespace app\modules\cabinet\services;

use app\modules\cabinet\form\RegistrationWithVerifyForm;
use app\modules\cabinet\models\OAuth;
use Yii;
use yii\authclient\ClientInterface;
use yii\web\IdentityInterface;

/**
 * Class OAuthService
 *
 * @package app\modules\cabinet\services
 */
class OAuthService
{
    /**
     * @var string
     */
    protected $sessionKey = 'registrationWithOAuth';

    /**
     * @param RegistrationWithVerifyForm $form
     * @param ClientInterface $client
     */
    public function make(RegistrationWithVerifyForm $form, ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();
        $form->setAttributes([
            'email' => $attributes['email'],
        ]);
    }

    /**
     * @param ClientInterface $client
     */
    public function save(ClientInterface $client)
    {
        $attributes = $client->getUserAttributes();

        Yii::$app->getSession()->set($this->sessionKey, [
            'source' => $client->getId(),
            'source_id' => (string)$attributes['id'],
        ]);
    }

    /**
     * @param OAuth $OAuth
     * @param IdentityInterface $model
     *
     * @return bool
     */
    public function flush(OAuth $OAuth, IdentityInterface $model)
    {
        $attributes = Yii::$app->getSession()->get($this->sessionKey);

        if ($attributes) {
            $OAuth->setAttributes($attributes);
            $OAuth->client_id = $model->getId();

            return $OAuth->save();
        }

        return false;
    }
}
