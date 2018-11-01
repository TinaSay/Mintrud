<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 22.09.2017
 * Time: 14:20
 */

// declare(strict_types=1);


namespace app\assets;


use yii\web\AssetBundle;

class BloodhoundAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/typeahead.js/dist';

    /**
     * @var array
     */
    public $js = [
        'bloodhound.min.js'
    ];
}