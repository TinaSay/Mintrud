<?php

namespace app\modules\redirect\models\query;

use app\modules\redirect\models\Redirect;

/**
 * This is the ActiveQuery class for [[Redirect]].
 *
 * @see Redirect
 */
class RedirectQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Redirect[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Redirect|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return RedirectQuery
     */
    public function hidden($hidden = Redirect::HIDDEN_NO): self
    {
        return $this->andWhere([Redirect::tableName() . '.[[hidden]]' => $hidden]);
    }
}
