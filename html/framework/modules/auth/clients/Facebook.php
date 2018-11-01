<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 22.08.15
 * Time: 23:39
 */

namespace app\modules\auth\clients;

/**
 * Class Facebook
 *
 * @package app\modules\auth\clients
 */
class Facebook extends \yii\authclient\clients\Facebook
{
    /**
     * @var string
     */
    public $authUrl = 'https://www.facebook.com/dialog/oauth?display=popup';

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
        return 'Facebook';
    }
}
