<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 30.11.17
 * Time: 15:50
 */

namespace app\modules\auth\filters;


use app\modules\auth\models\Auth;
use yii\base\ActionFilter;
use yii\di\Instance;
use yii\web\User;

/**
 * Class IpControl
 * @package app\modules\auth\filters
 */
class IpControl extends ActionFilter
{
    /** @var User */
    public $user = 'user';

    /**
     *
     */
    public function init()
    {
        parent::init();
        $this->user = Instance::ensure($this->user, User::class);
    }


    /**
     * @param \yii\base\ActionEvent $event
     */
    public function beforeFilter($event)
    {
        /** @var Auth $user */
        $user = $this->user->identity;
        if (is_null($user)) {
            return;
        }
        $ip = \Yii::$app->request->getUserIP();
        if (!$user->allowByIp($ip)) {
            $this->user->logout();
            \Yii::$app->session->addFlash('error', 'Не соответствует IP');
            $this->user->loginRequired();
        }
        return;
    }
}