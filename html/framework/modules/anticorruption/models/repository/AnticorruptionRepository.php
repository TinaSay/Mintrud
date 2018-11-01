<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11.08.2017
 * Time: 16:08
 */

// declare(strict_types=1);


namespace app\modules\anticorruption\models\repository;


use app\modules\anticorruption\models\Anticorruption;
use yii\web\NotFoundHttpException;

/**
 * Class AnticorruptionRepository
 * @package app\modules\anticorruption\models\repository
 */
class AnticorruptionRepository
{
    /**
     * @param int $id
     * @return Anticorruption
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): Anticorruption
    {
        $model = Anticorruption::find()
            ->andWhere(['id' => $id])
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }

    /**
     * @param string $url
     * @return Anticorruption
     * @throws NotFoundHttpException
     */
    public function findOneByUrl(string $url): Anticorruption
    {
        $model = Anticorruption::find()
            ->andWhere(['url' => $url])
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }
}