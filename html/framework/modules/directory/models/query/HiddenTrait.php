<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 18:35
 */

declare(strict_types = 1);

namespace app\modules\directory\models\query;

use app\modules\directory\models\Directory;
use yii\db\ActiveQuery;

trait HiddenTrait
{
    /**
     * @param int $hidden
     * @return $this|ActiveQuery
     */
    public function hidden($hidden = Directory::HIDDEN_NO): ActiveQuery
    {
        return $this->andWhere([Directory::tableName() . '.[[hidden]]' => $hidden]);
    }
}