<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.08.15
 * Time: 23:36
 */

namespace app\modules\cabinet\clients;

/**
 * Class YandexOAuth
 *
 * @package app\modules\cabinet\clients
 */
class YandexOAuth extends \yii\authclient\clients\YandexOAuth
{
    public function init()
    {
        parent::init();
        $this->setViewOptions(
            [
                'popupWidth' => 590,
                'popupHeight' => 555,
            ]
        );
    }

    /**
     * @return string
     */
    protected function defaultTitle()
    {
        return 'Яндекс';
    }
}
