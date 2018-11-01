<?php

namespace app\modules\magic\widgets;

use app\modules\magic\models\Magic;
use yii\base\Widget;
use yii\helpers\ArrayHelper;

/**
 * Class MagicWidget
 *
 * @package app\modules\magic\widgets
 */
class MagicWidget extends Widget
{
    /**
     * @var array
     */
    protected $list = [];

    /**
     * @var null
     */
    public $tpl = null;

    /**
     * @var array
     */
    public $options = [];

    public function init()
    {
        parent::init();

        /* @var $row Magic */

        $model = Magic::find()->where($this->options)->orderBy(['position' => SORT_ASC, 'id' => SORT_ASC])->all();
        foreach ($model as $row) {
            $type = $row->getType();
            $this->list = ArrayHelper::merge(
                $this->list,
                [
                    $type => [
                        $row,
                    ],
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render($this->tpl, ['model' => $this->list]);
    }
}
