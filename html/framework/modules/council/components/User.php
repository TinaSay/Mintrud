<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 26.06.17
 * Time: 11:27
 */

namespace app\modules\council\components;

class User extends \yii\web\User
{
    /**
     * @var string
     */
    public $idParam = '__id_lk';

    /**
     * @var string
     */
    public $authTimeoutParam = '__expire_lk';

    /**
     * @var string
     */
    public $absoluteAuthTimeoutParam = '__absoluteExpire_lk';

    /**
     * @var string
     */
    public $returnUrlParam = '__returnUrl_lk';

}