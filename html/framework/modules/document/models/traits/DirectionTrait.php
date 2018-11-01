<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 19:07
 */

declare(strict_types = 1);

namespace app\modules\document\models\traits;

use app\modules\document\models\Direction;
use app\modules\document\models\DocumentDirection;
use app\modules\document\models\query\DirectionQuery;
use yii\db\ActiveQuery;

/**
 * Class DirectionTrait
 * @package app\modules\document\models\traits
 */
trait DirectionTrait
{
    /**
     * @return DirectionQuery|ActiveQuery
     */
    public function getDirections(): DirectionQuery
    {
        return $this->hasMany(Direction::class, ['id' => 'document_direction_id'])
            ->viaTable(DocumentDirection::tableName(), ['document_id' => 'id']);
    }


    /**
     * @return DirectionQuery
     */
    public function getDirectionsByHidden(): DirectionQuery
    {
        return $this->getDirections()->hidden();
    }
}