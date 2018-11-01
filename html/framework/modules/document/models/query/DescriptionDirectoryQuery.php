<?php

declare(strict_types = 1);

namespace app\modules\document\models\query;

use app\modules\directory\models\query\HiddenTrait;
use app\modules\directory\models\query\LanguageTrait;
use app\modules\document\models\DescriptionDirectory;

/**
 * This is the ActiveQuery class for [[\app\modules\document\models\DescriptionDirectory]].
 *
 * @see \app\modules\document\models\DescriptionDirectory
 */
class DescriptionDirectoryQuery extends \yii\db\ActiveQuery
{
    use HiddenTrait, LanguageTrait {
        HiddenTrait::hidden as directoryHidden;
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\DescriptionDirectory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \app\modules\document\models\DescriptionDirectory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @param int $hidden
     * @return DescriptionDirectoryQuery
     */
    public function hidden($hidden = DescriptionDirectory::HIDDEN_NO): DescriptionDirectoryQuery
    {
        return $this->andWhere([DescriptionDirectory::tableName() . '.[[hidden]]' => $hidden]);
    }

    /**
     * @param int $id
     * @return DescriptionDirectoryQuery
     */
    public function id(int $id): self
    {
        return $this->andWhere([DescriptionDirectory::tableName() . '.[[id]]' => $id]);
    }

    /**
     * @param int $directoryId
     * @return DescriptionDirectoryQuery
     */
    public function directory(int $directoryId): self
    {
        return $this->andWhere([DescriptionDirectory::tableName() . '.[[directory_id]]' => $directoryId]);
    }
}
