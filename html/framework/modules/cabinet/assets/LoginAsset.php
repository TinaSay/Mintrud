<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 16.02.16
 * Time: 14:26
 */

namespace app\modules\cabinet\assets;

use yii\web\AssetBundle;

/**
 * Class LoginAsset
 *
 * use:
 *
 * ```php
 * $this->registerJs(new JsExpression('jQuery(".cabinetLogin").cabinetLogin();'));
 * ```
 *
 * ```html
 * <a href="<?= Url::to(['/cabinet/login/oauth', 'authclient' => 'client']) ?>" class="cabinetLogin" data-popup-height="725" data-popup-width="910">Вход</a>
 * ```
 *
 * @package app\modules\cabinet\assets
 */
class LoginAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/cabinet/assets/dist';

    /**
     * @var array
     */
    public $js = [
        'login.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
