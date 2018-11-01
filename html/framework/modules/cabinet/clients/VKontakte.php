<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.08.15
 * Time: 23:37
 */

namespace app\modules\cabinet\clients;

/**
 * Class VKontakte
 *
 * @package app\modules\cabinet\clients
 */
class VKontakte extends \yii\authclient\clients\VKontakte
{
    public function init()
    {
        parent::init();
        $this->setViewOptions(
            [
                'popupWidth' => 656,
                'popupHeight' => 377,
            ]
        );
    }

    /**
     * @return string
     */
    protected function defaultTitle()
    {
        return 'ВК';
    }
}
