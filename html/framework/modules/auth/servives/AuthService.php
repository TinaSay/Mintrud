<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 27.11.17
 * Time: 18:56
 */

namespace app\modules\auth\servives;


use app\modules\auth\models\Auth;
use app\modules\auth\models\repositories\AuthRepository;
use app\modules\auth\types\LoginType;
use DomainException;
use Yii;

class AuthService
{
    /**
     * @var AuthRepository
     */
    private $authRepository;

    /**
     * AuthService constructor.
     * @param AuthRepository $authRepository
     */
    public function __construct(
        AuthRepository $authRepository
    )
    {
        $this->authRepository = $authRepository;
    }

    /**
     * @param LoginType $type
     * @param $ip
     */
    public function login(LoginType $type, $ip)
    {
        $auth = $this->authRepository->findOneByLoginBlocked($type->login, Auth::BLOCKED_NO);
        $this->authRepository->domainException($auth, 'Не верный пароль или логин');
        if (!$auth->allowByIp($ip)) {
            $this->domainException('Не соответствует IP');
        }

        if (!Yii::$app->getUser()->login($auth)) {
            $this->domainException();
        }
    }

    /**
     * @param string $message
     */
    public function domainException($message = 'Error')
    {
        throw new DomainException($message);
    }
}