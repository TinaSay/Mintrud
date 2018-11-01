<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 14:15
 */

namespace app\modules\tag\interfaces;


use yii\db\ActiveQuery;

/**
 * Interface TagInterface
 * @package app\modules\tag\interfaces
 */
interface TagInterface
{
    /**
     * @param int $id
     * @return ActiveQuery|TagInterface
     */
    public static function findModel(int $id): ActiveQuery;

    /**
     * @return string
     */
    public function getTitle(): string;

    /**
     * @return string
     */
    public function viewUrl(): string;
}