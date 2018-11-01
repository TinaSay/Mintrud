<?php

namespace app\modules\document\models\query;

use app\modules\document\models\WidgetOnMain;
use app\modules\typeDocument\models\query\HiddenTrait;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\WidgetOnMain]].
 *
 * @see \app\modules\document\models\WidgetOnMain
 */
class WidgetOnMainQuery extends \yii\db\ActiveQuery
{
    use HiddenTrait {
        HiddenTrait::hidden as typeHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\WidgetOnMain[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\WidgetOnMain|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return WidgetOnMainQuery
     */
    public function hidden($hidden = WidgetOnMain::HIDDEN_NO): self
    {
        return $this->andWhere([WidgetOnMain::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $order
     * @return WidgetOnMainQuery
     */
    public function orderByPosition($order = SORT_ASC): self
    {
        return $this->orderBy([WidgetOnMain::tableName() . '.[[position]]' => $order]);
    }
}
