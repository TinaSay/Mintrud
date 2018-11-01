<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.08.15
 * Time: 23:32
 */

namespace app\modules\cabinet\clients;

/**
 * Class GoogleOAuth
 *
 * @package app\modules\cabinet\clients
 */
class GoogleOAuth extends \yii\authclient\clients\GoogleOAuth
{
    public function init()
    {
        parent::init();
        $this->setViewOptions(
            [
                'popupWidth' => 590,
                'popupHeight' => 565,
            ]
        );
    }

    /**
     * @return string
     */
    protected function defaultTitle()
    {
        return 'Google';
    }
}
