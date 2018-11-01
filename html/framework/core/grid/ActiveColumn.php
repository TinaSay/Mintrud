<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 20.07.17
 * Time: 1:03
 */

namespace app\core\grid;

use yii\grid\DataColumn;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * Class ActiveColumn
 *
 * @package app\core\grid
 */
class ActiveColumn extends DataColumn
{
    /**
     * @var string
     */
    public $controller;

    /**
     * @var callable
     */
    public $urlCreator;

    /**
     * @var string
     */
    public $action = 'update';

    /**
     * @param string $action
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     *
     * @return mixed|string
     */
    public function createUrl($action, $model, $key, $index)
    {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        } else {
            $params = is_array($key) ? $key : ['id' => (string)$key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::to($params);
        }
    }

    /**
     * @param mixed $model
     * @param mixed $key
     * @param int $index
     *
     * @return string
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return Html::a($this->getDataCellValue($model, $key, $index),
                $this->createUrl($this->action, $model, $key, $index));
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }
}
