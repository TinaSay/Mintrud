<?php
/**
 * Created by PhpStorm.
 * User: 1
 * Date: 09.07.2017
 * Time: 19:37
 */

namespace app\modules\questionnaire\widgets\assets;


use yii\web\AssetBundle;

/**
 * Class FormAsset
 * @package app\modules\questionnaire\widgets\assets
 */
class FormAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $basePath = '@webroot/static/default/js/questionnaire';
    /**
     * @var string
     */
    public $baseUrl = '@web/static/default/js/questionnaire';

    /**
     * @var array
     */
    public $js = [
        'form.js'
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}