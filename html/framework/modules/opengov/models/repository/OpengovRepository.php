<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 13:45
 */

// declare(strict_types=1);


namespace app\modules\opengov\models\repository;


use app\modules\opengov\models\Opengov;
use yii\web\NotFoundHttpException;

/**
 * Class OpengovRepository
 * @package app\modules\opengov\models\repository
 */
class OpengovRepository
{

    /**
     * @param int $id
     * @return Opengov|null
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): Opengov
    {
        $model = Opengov::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        return $model;
    }
}