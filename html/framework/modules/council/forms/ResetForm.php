<?php
/**
 * Created by PhpStorm.
 * User: elfuvo
 * Date: 24.06.17
 * Time: 10:36
 */

namespace app\modules\council\forms;


use app\modules\council\models\CouncilMember;

/**
 * Class ResetForm
 * @package app\modules\council\forms
 */
class ResetForm extends CouncilMember
{
    /**
     * @var null
     */
    public $verifyCode = null;

    /**
     * @var CouncilMember
     */
    private $client = null;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [];
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['password'], 'string', 'max' => 512, 'min' => 8],
            [['password'], 'required'],
            [['reset_token'], 'string', 'max' => 128],
            [['reset_token'], 'required'],
            ['password', 'authorization'],
            ['verifyCode', 'captcha', 'captchaAction' => '/lk/login/captcha'],
        ];
    }

    public function authorization()
    {
        if (!$this->hasErrors()) {
            if (!$this->getCouncilMember()) {
                $this->addError('password', 'Код для сброса пароля указан неверно');
            }
        }
    }

    /**
     * @return CouncilMember
     */
    public function getCouncilMember()
    {
        if ($this->client === null) {
            $this->client = CouncilMember::findOne(['reset_token' => $this->reset_token, 'blocked' => CouncilMember::BLOCKED_NO]);
        }

        return $this->client;
    }
}
