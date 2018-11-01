<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 14.07.17
 * Time: 11:12
 */

namespace app\core\grid;

use app\interfaces\HiddenAttributeInterface;
use yii\grid\DataColumn;

/**
 * Class HiddenColumn
 *
 * @package app\core\grid
 */
class HiddenColumn extends DataColumn
{
    /**
     * @var string
     */
    public $attribute = 'hidden';

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
            if ($model instanceof HiddenAttributeInterface) {
                return $model->getHidden();
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
            if ($filterModel instanceof HiddenAttributeInterface) {
                $this->filter = $filterModel::getHiddenList();
            }
        }

        return parent::renderFilterCellContent();
    }
}
