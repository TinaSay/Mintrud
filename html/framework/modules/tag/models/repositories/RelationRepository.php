<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 22.08.2017
 * Time: 9:58
 */

namespace app\modules\tag\models\repositories;


use app\modules\tag\models\Relation;
use RuntimeException;

/**
 * Class RelationRepository
 * @package app\modules\tag\models\repositories
 */
class RelationRepository
{

    /**
     * @param Relation $model
     */
    public function save(Relation $model): void
    {
        if (!$model->save()) {
            throw new RuntimeException('Saving error');
        }
    }
}