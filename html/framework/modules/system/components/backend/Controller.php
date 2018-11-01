<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 10.02.17
 * Time: 11:31
 */

namespace app\modules\system\components\backend;

/**
 * Class Controller
 *
 * @package app\modules\system\components\backend
 */
class Controller extends \yii\web\Controller
{
    use CanTrait;

    /**
     * @var string
     */
    public $layout = '@app/modules/system/views/backend/layouts/index.php';
}
