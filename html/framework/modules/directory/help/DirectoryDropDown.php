<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 06.09.2017
 * Time: 15:34
 */

namespace app\modules\directory\help;

use app\modules\directory\models\Directory;
use yii\helpers\ArrayHelper;


/**
 * Class DirectoryDropDown
 * @package app\modules\directory\help
 */
class DirectoryDropDown
{
    /**
     * @param $exclude array
     * @param int|null $type
     * @param string|null $language
     * @return array
     */
    public static function list(array $exclude = [], int $type = null, string $language = null): array
    {
        return ArrayHelper::map(
            Directory::find()
                ->language($language)
                ->filterType($type)
                ->asTreeList($exclude),
            'id',
            'title'
        );
    }

    /**
     * @return array
     */
    public static function getSelectOptions(): array
    {
        $ids = Directory::find()->select('id')->andWhere(['depth' => 0])->column();
        $result = array_combine($ids, array_fill(0, count($ids), ['class' => 'font-weight-select']));
        return $result;
    }
}