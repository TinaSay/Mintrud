<?php

declare(strict_types = 1);

namespace app\modules\news\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\news\models\WidgetOnMain;


/**
 * This is the ActiveQuery class for [[\app\modules\news\models\WidgetOnMain]].
 *
 * @see \app\modules\news\models\WidgetOnMain
 */
class WidgetOnMainQuery extends \yii\db\ActiveQuery
{
    use LanguageTrait, HiddenTrait {
        HiddenTrait::hidden as directoryHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\news\models\WidgetOnMain[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\news\models\WidgetOnMain|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return WidgetOnMainQuery
     */
    public function hidden($hidden = WidgetOnMain::HIDDEN_NO): WidgetOnMainQuery
    {
        return $this->andWhere([WidgetOnMain::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $sort
     * @return WidgetOnMainQuery
     */
    public function orderByPosition($sort = SORT_ASC): WidgetOnMainQuery
    {
        return $this->addOrderBy([WidgetOnMain::tableName() . '.[[position]]' => $sort]);
    }
}
