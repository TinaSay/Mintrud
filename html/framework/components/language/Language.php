<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.02.17
 * Time: 15:46
 */

namespace app\components\language;

use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class Language
 *
 * @package app\components\language
 */
class Language extends Component
{
    /**
     * @var array
     */
    public $list = [
        [
            'iso' => 'ru-RU',
            'title' => 'Русский',
        ],
    ];

    /**
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param string $iso
     *
     * @return bool
     */
    public function has($iso)
    {
        if (in_array($iso, ArrayHelper::getColumn($this->getList(), 'iso'))) {
            return true;
        } else {
            return false;
        }
    }
}
