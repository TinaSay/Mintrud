<?php

/**
 * Created by PhpStorm.
 * User: krok
 * Date: 16.11.16
 * Time: 12:32
 */

namespace app\widgets\sortable\actions;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Exception;

/**
 * Class UpdateAllAction
 * @package app\widgets\sortable\actions
 */
class UpdateAllAction extends \yii\base\Action
{
    /**
     * @var array
     */
    public $items = [];

    /**
     * @var null|ActiveRecord
     */
    public $model = null;

    /**
     * @var string
     */
    public $positionAttribute = 'position';

    public function init()
    {
        parent::init();

        if (!($this->model instanceof ActiveRecord)) {
            throw new InvalidConfigException('The "model" property must be set.');
        }
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        $position = 0;
        $class = $this->model;

        if (is_array($this->items)) {
            foreach ($this->items as $id) {
                $model = $class::findOne($id);
                if ($model instanceof ActiveRecord) {
                    $model->setAttribute($this->positionAttribute, ++$position);
                    if (!$model->save()) {
                        throw new Exception('', $model->getErrors());
                    }
                }
            }
        }
    }
}
