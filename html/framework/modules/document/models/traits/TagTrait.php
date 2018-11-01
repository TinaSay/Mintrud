<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.07.2017
 * Time: 15:26
 */

namespace app\modules\document\models\traits;


use app\modules\document\models\Document;
use app\modules\document\models\query\DocumentQuery;
use yii\db\ActiveQuery;

trait TagTrait
{
    /**
     * @param int $id
     * @return DocumentQuery|ActiveQuery
     */
    public static function findModel(int $id): ActiveQuery
    {
        return Document::find()->hidden()->id($id);
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