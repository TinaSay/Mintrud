<?php

namespace app\modules\config\helpers;

use app\modules\config\models\Config as Model;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class Config
{
    /**
     * @var array
     */
    static private $_config = [];

    /**
     * @return array
     */
    public static function listing()
    {
        if (self::$_config == []) {
            self::$_config = ArrayHelper::index(Model::find()->all(), 'name');
        }

        return self::$_config;
    }

    /**
     * @param string $name
     * @return bool
     */
    public static function has($name)
    {
        return ArrayHelper::keyExists($name, self::listing());
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public static function get($name)
    {
        if (self::has($name)) {
            return ArrayHelper::getValue(self::listing(), $name);
        } else {
            throw new InvalidConfigException('Configuration not found');
        }
    }

    /**
     * @param $name
     * @return string|null
     */
    public static function getValue($name)
    {
        if (self::has($name)) {
            return ArrayHelper::getValue(self::listing(), $name . '.value');
        }
        return null;
    }
}
