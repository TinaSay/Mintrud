<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 17:40
 */

declare(strict_types = 1);

namespace app\modules\document\models\traits;


use app\modules\typeDocument\models\query\TypeQuery;
use app\modules\typeDocument\models\Type;

/**
 * Class TypeDocumentTrait
 * @package app\modules\document\models\traits
 */
trait TypeDocumentTrait
{
    /**
     * @return \yii\db\ActiveQuery|TypeQuery
     */
    public function getType(): TypeQuery
    {
        return $this->hasOne(Type::className(), ['id' => 'type_document_id']);
    }

    /**
     * @return TypeQuery
     */
    public function getTypeByHidden()
    {
        return $this->getType()->hidden();
    }
}