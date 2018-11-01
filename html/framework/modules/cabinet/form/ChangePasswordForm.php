<?php
/**
 * Created by PhpStorm.
 * User: krok
 * Date: 17.07.17
 * Time: 18:03
 */

namespace app\modules\cabinet\form;

use app\core\validators\StrengthValidator;
use app\modules\cabinet\models\Client;
use Yii;
use yii\base\Model;

/**
 * Class ChangePasswordForm
 *
 * @package app\modules\cabinet\form
 */
class ChangePasswordForm extends Model
{
    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $newPassword;

    /**
     * @var Client|null
     */
    private $client;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['password', 'newPassword'], 'required'],
            [['password', 'newPassword'], 'string', 'max' => 512, 'min' => 8],
            [['password'], 'validatePassword'],
            [['newPassword'], StrengthValidator::className()],
        ];
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'password',
            'newPassword',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Введите старый пароль',
            'newPassword' => 'Придумайте новый пароль',
        ];
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    /**
     * @param string $newPassword
     */
    public function setNewPassword(string $newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function validatePassword()
    {
        $model = $this->getClient();

        if (!$model || !Yii::$app->getSecurity()->validatePassword($this->password, $model->password)) {
            $this->addError('password', 'Неправильный пароль');
        }
    }

    /**
     * @return Client|null
     */
    public function getClient()
    {
        if ($this->client === null) {
            $this->client = Client::findOne(['id' => Yii::$app->getUser()->getId()]);
        }

        return $this->client;
    }
}
