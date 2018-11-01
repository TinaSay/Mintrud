<?php

declare(strict_types=1);

/**
 * Created by PhpStorm.
 * User: 1
 * Date: 07.07.2017
 * Time: 20:07
 */

namespace app\modules\questionnaire\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * Class Type
 * @package app\modules\questionnaire\models
 */
class Type extends Model
{
    const TYPE_ID_TEXT = 1;
    const TYPE_ID_RADIO = 2;
    const TYPE_ID_CHECKBOX = 3;
    const TYPE_ID_SELECT = 4;
    const TYPE_ID_TEXTAREA = 5;
    const TYPE_ID_SELECT_MULTIPLE = 6;
    const TYPE_ID_NONE = 0;

    /**
     * @var
     */
    public $id;
    /**
     * @var
     */
    public $type;

    /**
     * @var array
     */
    public static $list = [
        '1' => [
            'id' => '1',
            'type' => 'text',
        ],
        '2' => [
            'id' => '2',
            'type' => 'radio',
        ],
        '3' => [
            'id' => '3',
            'type' => 'checkbox',
        ],
        '4' => [
            'id' => '4',
            'type' => 'select',
        ],
        '5' => [
            'id' => '5',
            'type' => 'textarea',
        ],
        '6' => [
            'id' => '6',
            'type' => 'select(multiple)',
        ],
    ];

    /**
     * @return array
     */
    public static function getDropDown(): array
    {
        return ArrayHelper::map(static::$list, 'id', 'type');
    }

    /**
     * @param int $id
     * @return Type|null
     */
    public static function findOne(int $id): ?Type
    {
        return isset(static::$list[$id]) ? new Type(static::$list[$id]) : null;
    }
}