<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.08.15
 * Time: 23:40
 */

namespace app\modules\auth\clients;

/**
 * Class Twitter
 *
 * @package app\modules\auth\clients
 */
class Twitter extends \yii\authclient\clients\Twitter
{
    public function init()
    {
        parent::init();
        $this->setViewOptions(
            [
                'popupWidth' => 730,
                'popupHeight' => 790,
            ]
        );
    }

    /**
     * @return string
     */
    protected function defaultTitle()
    {
        return 'Twitter';
    }
}
