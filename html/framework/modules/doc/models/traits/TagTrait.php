<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 14:24
 */

declare(strict_types = 1);


namespace app\modules\doc\models\traits;


use app\modules\doc\models\Doc;
use app\modules\doc\models\query\DocQuery;
use yii\db\ActiveQuery;

/**
 * Class TagTrait
 * @package app\modules\doc\traits
 */
trait TagTrait
{
    /**
     * @param int $id
     * @return DocQuery|ActiveQuery
     */
    public static function findModel(int $id): ActiveQuery
    {
        return Doc::find()->hidden()->id($id);
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function viewUrl(): string
    {
        return $this->url;
    }
}