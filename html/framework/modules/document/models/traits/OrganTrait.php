<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 25.07.2017
 * Time: 17:49
 */

declare(strict_types = 1);


namespace app\modules\document\models\traits;


use app\modules\organ\models\Organ;
use app\modules\organ\models\query\OrganQuery;

/**
 * Class OrganTrait
 * @package app\modules\document\models\traits
 */
trait OrganTrait
{
    /**
     * @return OrganQuery|\yii\db\ActiveQuery
     */
    public function getOrgan(): OrganQuery
    {
        return $this->hasOne(Organ::className(), ['id' => 'organ_id']);
    }


    /**
     * @return OrganQuery
     */
    public function getOrganByHidden()
    {
        return $this->getOrgan()->hidden();
    }
}