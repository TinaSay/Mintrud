<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 23.08.2017
 * Time: 11:06
 */

namespace app\modules\atlas\models\repositories;


use app\modules\atlas\models\AtlasAllowance;

/**
 * Class AtlasAllowanceRepository
 * @package app\modules\atlas\models\repositories
 */
class AtlasAllowanceRepository
{
    /**
     * @param AtlasAllowance $model
     */
    public function save(AtlasAllowance $model): void
    {
        if (!$model->save()) {
            throw new \RuntimeException('Saving error');
        }
    }
}