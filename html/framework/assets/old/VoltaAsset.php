<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.06.2017
 * Time: 11:34
 */

// declare(strict_types=1);


namespace app\assets\old;


use yii\web\AssetBundle;

/**
 * Class VoltaAsset
 * @package app\assets
 */
class VoltaAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $baseUrl = 'https://voltajs.org';

    /**
     * @var array
     */
    public $js = [
        'volta-latest.min.js',
    ];

}