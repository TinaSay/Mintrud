<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.07.2017
 * Time: 20:28
 */

declare(strict_types = 1);


namespace app\modules\tag\models\query;


use app\modules\tag\models\Tag;
use yii\db\ActiveQuery;

trait TName
{
    /**
     * @param string $name
     * @return static|ActiveQuery
     */
    public function name(string $name): ActiveQuery
    {
        return $this->andWhere([Tag::tableName() . '.[[name]]' => $name]);
    }
}