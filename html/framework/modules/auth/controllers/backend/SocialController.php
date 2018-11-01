<?php

namespace app\modules\auth\controllers\backend;

use app\modules\system\components\backend\Controller;

/**
 * Class SocialController
 *
 * @package app\modules\auth\controllers\backend
 */
class SocialController extends Controller
{
    /**
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
