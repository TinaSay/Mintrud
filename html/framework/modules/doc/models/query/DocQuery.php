<?php

declare(strict_types = 1);

namespace app\modules\doc\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\doc\models\Doc;

/**
 * This is the ActiveQuery class for [[\app\modules\doc\models\Doc]].
 *
 * @see \app\modules\doc\models\Doc
 */
class DocQuery extends \yii\db\ActiveQuery
{
    use LanguageTrait, HiddenTrait {
        HiddenTrait::hidden as directoryHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\doc\models\Doc[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\doc\models\Doc|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $id
     * @return DocQuery
     */
    public function id(int $id): DocQuery
    {
        return $this->andWhere([Doc::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param $hidden
     * @return DocQuery
     */
    public function hidden($hidden = Doc::HIDDEN_NO): DocQuery
    {
        return $this->andWhere([Doc::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $order
     * @return DocQuery
     */
    public function orderByDate($order = SORT_DESC): DocQuery
    {
        return $this->orderBy([Doc::tableName() . '.[[date]]' => $order]);
    }

    /**
     * @param int $directory
     * @return DocQuery
     */
    public function directory(int $directory): DocQuery
    {
        return $this->andWhere([Doc::tableName() . '.[[directory_id]]' => $directory]);
    }
}
