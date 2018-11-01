<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.07.2017
 * Time: 15:53
 */

namespace app\modules\news\models\traits;


use app\modules\news\models\News;
use app\modules\news\models\query\NewsQuery;
use yii\db\ActiveQuery;

/**
 * Class TagTrait
 * @package app\modules\news\models\traits
 */
trait TagTrait
{

    /**
     * @param int $id
     * @return NewsQuery|ActiveQuery
     */
    public static function findModel(int $id): ActiveQuery
    {
        return News::find()->hidden()->id($id);
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
        return $this->getUrl();
    }
}