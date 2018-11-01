<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 17.11.17
 * Time: 14:29
 */

namespace app\modules\auth\servives;


use app\modules\auth\models\repositories\AuthRepository;
use app\modules\auth\types\ProfileType;

/**
 * Class ProfileService
 * @package app\modules\auth\servives
 */
class ProfileService
{
    /**
     * @var AuthRepository
     */
    private $authRepository;

    /**
     * ProfileService constructor.
     * @param AuthRepository $authRepository
     */
    public function __construct(AuthRepository $authRepository)
    {

        $this->authRepository = $authRepository;
    }


    /**
     * @param int $id
     * @param ProfileType $type
     * @param string $ip
     * @return \app\modules\auth\models\Auth|null
     */
    public function updateBindIp(int $id, ProfileType $type, string $ip)
    {
        $model = $this->authRepository->findOne($id);
        $this->authRepository->notFoundHttpException($model);

        $model->bind_ip = $type->bind_ip;

        if ($model->isBindIp()) {
            $model->setIp($ip);
        } elseif($model->isDynamicIp()) {
            $model->dynamicIp = $type->dynamicIp;
        } else {
            $model->ip = null;
        }
        $model->updateAttributes(['ip', 'bind_ip', 'dynamicIp']);

        return $model;
    }
}