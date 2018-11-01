<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 14.07.17
 * Time: 10:30
 */

namespace app\core\grid;

use app\interfaces\BlockedAttributeInterface;
use yii\grid\DataColumn;

/**
 * Class BlockedColumn
 *
 * @package app\core\grid
 */
class BlockedColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'blocked';

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     *
     * @return string
     */
    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value === null) {
            if ($model instanceof BlockedAttributeInterface) {
                return $model->getBlocked();
            }
        }

        return parent::getDataCellValue($model, $key, $index);
    }

    /**
     * @return string
     */
    protected function renderFilterCellContent()
    {
        if ($this->filter === null) {
            $filterModel = $this->grid->filterModel;
            if ($filterModel instanceof BlockedAttributeInterface) {
                $this->filter = $filterModel::getBlockedList();
            }
        }

        return parent::renderFilterCellContent();
    }
}
