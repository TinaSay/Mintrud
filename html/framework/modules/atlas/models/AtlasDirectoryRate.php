<?php

namespace app\modules\atlas\models;


use app\modules\atlas\interfaces\TypeInterface;
use yii\helpers\ArrayHelper;

class AtlasDirectoryRate extends AtlasDirectory implements TypeInterface
{

    const STAT_TYPE_YEAR = 1;
    const STAT_TYPE_YEAR_DIFF = 2;
    const STAT_TYPE_NONE = 3;

    const VALUE_YES = 1;
    const VALUE_NO = 0;

    /**
     * @return int
     */
    public static function getType(): int
    {
        return static::TYPE_RATE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'Типы и группы показателей';
    }


    /**
     * ugly selection for dropdown with optgroup
     *
     * @return array
     */
    public static function asDropDown()
    {
        $list = static::find()
            ->select(['id', 'parent_id', 'title'])
            ->where(
                [
                    'type' => static::getType(),
                ]
            )->orderBy([
                'parent_id' => SORT_ASC,
                'position' => SORT_ASC,
            ])
            ->asArray()->all();

        $newList = $parents = [];
        foreach ($list as $item) {
            // this is a parent row
            if (empty($item['parent_id'])) {
                $newList[$item['title']] = [];
                $parents[$item['id']] = $item['title'];
            } else {
                // this is a child row
                $parentTitle = ArrayHelper::getValue($parents, $item['parent_id']);
                if ($parentTitle) {
                    $newList[$parentTitle][$item['id']] = $item['title'];
                }
            }
        }

        return $newList;
    }
}
