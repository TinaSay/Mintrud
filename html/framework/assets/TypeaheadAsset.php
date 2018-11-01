<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 21.09.2017
 * Time: 18:55
 */

// declare(strict_types=1);


namespace app\assets;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class TypeaheadAsset
 * @package app\assets
 */
class TypeaheadAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@bower/typeahead.js/dist';

    /**
     * @var array
     */
    public $js = [
        'typeahead.jquery.min.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        JqueryAsset::class,
    ];
}