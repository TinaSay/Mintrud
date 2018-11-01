<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 11:44
 */

// declare(strict_types=1);


namespace app\assets\old;


use yii\web\AssetBundle;

/**
 * Class LofficielmodeAsset
 * @package app\assets
 */
class LofficielmodeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $baseUrl = '//lofficielmode.ru/';

    /**
     * @var array
     */
    public $js = [
        'widget.js',
    ];
}