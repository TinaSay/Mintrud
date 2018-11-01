<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.07.2017
 * Time: 14:08
 */

namespace app\modules\typeDocument\models\query;


use app\modules\document\models\query\WidgetOnMainQuery;
use app\modules\typeDocument\models\Type;
use yii\db\Query;

trait HiddenTrait
{
    /**
     * @param int $hidden
     * @return Query|WidgetOnMainQuery|TypeQuery
     */
    public function hidden($hidden = Type::HIDDEN_NO): Query
    {
        return $this->andWhere([Type::tableName() . '.[[hidden]]' => $hidden]);
    }
}