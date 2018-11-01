<?php
/**
 * Created by PhpStorm.
 * User: cherem
 * Date: 17.11.17
 * Time: 14:33
 */

namespace app\modules\auth\models\repositories;


use app\modules\auth\models\Auth;
use DomainException;
use RuntimeException;
use yii\web\NotFoundHttpException;

/**
 * Class AuthRepository
 * @package app\modules\auth\models\repositories
 */
class AuthRepository
{
    /**
     * @param $id
     * @return Auth|null
     */
    public function findOne($id)
    {
        return Auth::findOne($id);
    }

    /**
     * @param Auth|null $model
     * @throws NotFoundHttpException
     */
    public function notFoundHttpException(Auth $model = null)
    {
        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
    }

    /**
     * @param Auth|null $model
     * @param string $message
     */
    public function domainException(Auth $model = null, string $message = 'Error')
    {
        if (is_null($model)) {
            throw new DomainException($message);
        }
    }

    public function existByIp($ip)
    {
        return Auth::find()->ip($ip)->bindIp()->exists();
    }

    /**
     * @param Auth $model
     */
    public function save(Auth $model)
    {
        if (!$model->save()) {
            throw new RuntimeException('Saving error');
        }
    }

    /**
     * @param $login
     * @param $blocked
     * @return Auth|array|null
     */
    public function findOneByLoginBlocked($login, $blocked)
    {
        return Auth::find()->login($login)->blocked($blocked)->limit(1)->one();
    }
}