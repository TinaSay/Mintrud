<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 18:06
 */

// declare(strict_types=1);


namespace app\modules\govserv\models\repositories;


use app\modules\govserv\models\Govserv;
use yii\web\NotFoundHttpException;

/**
 * Class GovservRepository
 * @package app\modules\govserv\models\repositories
 */
class GovservRepository
{
    /**
     * @param int $id
     * @return Govserv
     * @throws NotFoundHttpException
     */
    public function findOne(int $id): Govserv
    {
        $model = Govserv::find()
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
     * @return Govserv
     * @throws NotFoundHttpException
     */
    public function findOneByUrl(string $url): Govserv
    {
        $model = Govserv::find()
            ->andWhere(['url' => $url])
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }
        return $model;
    }
}