<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 10.08.2017
 * Time: 9:46
 */

// declare(strict_types=1);


namespace app\modules\programm\models\repository;


use app\modules\programm\models\Programm;
use yii\web\NotFoundHttpException;

/**
 * Class ProgrammRepository
 * @package app\modules\programm\models\repository
 */
class ProgrammRepository
{

    /**
     * @param int $id
     * @return Programm|array|null
     * @throws NotFoundHttpException
     */
    public function findOne(int $id)
    {
        $model = Programm::find()
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
     * @return Programm|array|null
     * @throws NotFoundHttpException
     */
    public function findOneByUrl(string $url)
    {
        $model = Programm::find()
            ->andWhere(['url' => $url])
            ->limit(1)
            ->one();

        if (is_null($model)) {
            throw new NotFoundHttpException('The required page does not exist');
        }

        return $model;
    }
}