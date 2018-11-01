<?php
/**
 * Created by PhpStorm.
 * User: nsign
 * Date: 09.04.18
 * Time: 13:25
 */

namespace app\modules\ministry\services;

use app\modules\ministry\models\MinistryAssignment;

/**
 * Class MinistryAssignmentService
 *
 * @package app\modules\ministry\services
 */
class MinistryAssignmentService
{
    /**
     * @param int|null $auth_id
     * @param int $ministry_id
     *
     * @return bool
     */
    public function checkIfExists(?int $auth_id, int $ministry_id)
    {
        return MinistryAssignment::find()->where([
            'auth_id' => $auth_id,
            'ministry_id' => $ministry_id,
        ])->exists();
    }

    /**
     * @param int|null $auth_id
     * @param int $ministry_id
     */
    public function saveNewRecord(?int $auth_id, int $ministry_id)
    {
        $MinistryAssignmentModel = new MinistryAssignment([
            'auth_id' => $auth_id,
            'ministry_id' => $ministry_id,
        ]);
        $MinistryAssignmentModel->save();
    }
}
