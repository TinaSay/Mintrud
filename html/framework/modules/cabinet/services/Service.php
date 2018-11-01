<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 23.06.17
 * Time: 17:43
 */

namespace app\modules\cabinet\services;

use app\modules\system\components\backend\CanTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * Class Service
 *
 * @package app\modules\cabinet\services
 */
class Service
{
    use CanTrait;

    /**
     * @param ActiveRecord $model
     */
    public function view(ActiveRecord $model)
    {
        $this->can(['model' => $model]);
    }

    /**
     * @param ActiveRecord $model
     *
     * @return bool
     */
    public function create(ActiveRecord $model)
    {
        if ($result = $model->validate()) {

            $password = $model->getAttribute('password');

            $model->setAttribute('password', Yii::$app->getSecurity()->generatePasswordHash($password));
            $model->setAttribute('auth_key', Yii::$app->getSecurity()->generateRandomString(64));
            $model->setAttribute('access_token', Yii::$app->getSecurity()->generateRandomString(128));
            $model->setAttribute('reset_token', Yii::$app->getSecurity()->generateRandomString(128));

            $result = $model->save();
        }

        return $result;
    }

    /**
     * @param ActiveRecord $model
     *
     * @return bool
     */
    public function update(ActiveRecord $model)
    {
        $this->can(['model' => $model]);

        if ($result = $model->validate()) {

            if ($model->getDirtyAttributes(['password'])) {
                $password = $model->getAttribute('password');

                $model->setAttribute('password', Yii::$app->getSecurity()->generatePasswordHash($password));
                $model->setAttribute('auth_key', Yii::$app->getSecurity()->generateRandomString(64));
                $model->setAttribute('access_token', Yii::$app->getSecurity()->generateRandomString(128));
                $model->setAttribute('reset_token', Yii::$app->getSecurity()->generateRandomString(128));
            }

            $result = $model->save();
        }

        return $result;
    }

    /**
     * @param ActiveRecord $model
     *
     * @return false|int
     */
    public function delete(ActiveRecord $model)
    {
        $this->can(['model' => $model]);

        return $model->delete();
    }
}
